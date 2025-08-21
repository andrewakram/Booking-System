<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Validation\ValidationException;

class BookingRepository implements BookingRepositoryInterface {

    public function getAvailability(array $data)
    {
        $service = $this->getServiceDetails($data);

        if (!$service) {
            throw ValidationException::withMessages([
                'service' => ["Sorry! this service not available at your zone"],
            ]);
        }

        if (!$service->is_published) {
            throw ValidationException::withMessages([
                'service' => ["Sorry! this service not available at this time"],
            ]);
        }

        $slots = [];

        // next 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = now($data['time_zone'])->addDays($i)->startOfDay();
            $dayName = strtolower($date->format('l')); // monday, tuesday...

            // Find provider availability for this weekday
            $availabilities = $service->provider->availabilities
                ->where('day_of_week', $dayName);

            // Get overrides for this date (only once, outside the slot loop)
            $overrides = $service->provider
                ->availabilityOverrides()
                ->whereDate('date', $date->toDateString())
                ->get();

            foreach ($availabilities as $a) {
                $start = Carbon::parse($a->start_time, $data['time_zone'])->setDateFrom($date);
                $end   = Carbon::parse($a->end_time, $data['time_zone'])->setDateFrom($date);

                foreach (CarbonPeriod::create($start, $service->duration . ' minutes', $end) as $slotStart) {
                    $slotEnd = $slotStart->copy()->addMinutes($service->duration);

                    // check overrides ----
                    $isBlocked = $overrides->contains(function ($o) use ($slotStart, $slotEnd, $date, $data) {
                        if ($o->is_available) return false;

                        // full day block (start_time and end_time are null)
                        if (empty($o->start_time) && empty($o->end_time)) {
                            return true;
                        }

                        // build Carbon objects with date + stored time
                        $blockStart = Carbon::parse($date->toDateString() . ' ' . ($o->start_time ?? '00:00:00'), $data['time_zone']);
                        $blockEnd   = Carbon::parse($date->toDateString() . ' ' . ($o->end_time   ?? '23:59:59'), $data['time_zone']);

                        return $slotStart < $blockEnd && $slotEnd > $blockStart;
                    });


                    // ---- 🔴 check bookings ----
                    $isBooked = Booking::where('service_id', $service->id)
                        ->where(function ($q) use ($slotStart, $slotEnd) {
                            $q->where('start_time', '<', $slotEnd)
                                ->where('end_time', '>', $slotStart);
                        })
                        ->exists();

                    if (!$isBlocked && !$isBooked) {
                        $slots[$date->toDateString()][] = [
                            'start' => $slotStart->format('H:i'),
                            'end'   => $slotEnd->format('H:i'),
                        ];
                    }
                }
            }

            // Ensure unique slots per day
            if (!empty($slots[$date->toDateString()])) {
                $slots[$date->toDateString()] = collect($slots[$date->toDateString()])
                    ->unique(fn ($s) => $s['start'] . '-' . $s['end'])
                    ->values()
                    ->all();
            }
        }

        return $slots;
    }


    public function book(array $data)
    {

        $today = Carbon::today();
        $bookingDate = Carbon::createFromFormat('Y-m-d', $data['date']);

        if ($bookingDate->lt($today)) {
            throw ValidationException::withMessages([
                'date' => ["Sorry! You cannot book a service in the past"],
            ]);
        }

        $service = $this->getServiceDetails($data);

        if (!$service) {
            throw ValidationException::withMessages([
                'service' => ["Sorry! this service not available at your zone"],
            ]);
        }

        if (!$service->is_published) {
            throw ValidationException::withMessages([
                'service' => ["Sorry! this service not available at this time"],
            ]);
        }

        // Parse start and end time
        $startTime = Carbon::createFromFormat('H:i:s', $data['start_time']);
        $endTime   = $startTime->copy()->addMinutes($service->duration);

        // Check overlapping bookings
        $overlap = Booking::where('provider_id', $service->provider_id)
            ->where('location_id', $data['location_id'])
            ->where('date', $data['date'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhereBetween('end_time', [$startTime->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime->format('H:i:s'))
                            ->where('end_time', '>=', $endTime->format('H:i:s'));
                    });
            })
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_time' => ['This time slot is already booked.'],
            ]);
        }

        // Create booking
        return Booking::create([
            'customer_id' => auth()->id(),
            'provider_id' => $service->provider_id,
            'service_id'  => $service->id,
            'location_id' => $data['location_id'],
            'date'        => $data['date'],
            'start_time'  => $startTime->format('H:i:s'),
            'end_time'    => $endTime->format('H:i:s'),
            'status'      => 'pending',
            'price'       => $service->price,
        ]);
    }

    private function getServiceDetails($data)
    {
        return Service::whereId($data['service_id'])
            ->whereHas('provider.locations', function ($query) use ($data) {
                $query->where('time_zone', $data['time_zone']);
            })
            ->with([
                'provider.locations',
                'provider.availabilities',
                'provider.availabilityOverrides'
            ])
            ->first();
    }



}
