<?php

namespace App\Repositories;

use App\Events\BookingStatusChanged;
use App\Interfaces\BookingStatusRepositoryInterface;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;

class BookingStatusRepository implements BookingStatusRepositoryInterface {

    protected array $transitions = [
        Booking::STATUS_PENDING => [Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED],
        Booking::STATUS_CONFIRMED => [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED],
        Booking::STATUS_CANCELLED => [], // no transition
        Booking::STATUS_COMPLETED => [], // no transition
    ];

    public function updateStatus(Booking $booking, array $data)
    {
        $current = $booking->first()->status;
        $newStatus = $data['status'];

        if (! isset($this->transitions[$current])) {
            throw ValidationException::withMessages([
                'status' => ["Invalid current status [$current]"],
            ]);
        }

        if (! in_array($newStatus, $this->transitions[$current])) {
            throw ValidationException::withMessages([
                'status' => ["Cannot change status from $current to $newStatus"],
            ]);
        }

        $booking->first()->update(['status' => $newStatus]);

        event(new BookingStatusChanged($booking, $current, $newStatus));

        return $booking->first();
    }



}
