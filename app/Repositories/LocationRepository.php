<?php

namespace App\Repositories;

use App\Interfaces\LocationRepositoryInterface;
use App\Models\Availability;
use App\Models\AvailabilityOverride;
use App\Models\Location;

class LocationRepository implements LocationRepositoryInterface {

    public function addMultiLocation(array $data)
    {
        \DB::beginTransaction();

        foreach ($data['data'] as $item){
            $location = Location::create([
                'provider_id'   => auth()->id(),
                'name'          => $item['name'],
                'time_zone'     => $item['time_zone'],
                'lat'           => $item['lat'] ?? null,
                'lng'           => $item['lng'] ?? null,
            ]);

            $this->addLocationAvailabilityToProvider($location->id,$item['availability']);

            $this->addLocationAvailabilityOverrideToProvider($location->id,$item['availability_override']);
        }

        \DB::commit();

        return true;
    }

    private function addLocationAvailabilityToProvider($locationId,$availability)
    {
        foreach ($availability as $a){
            Availability::create([
                'provider_id'   => auth()->id(),
                'location_id'   => $locationId,
                'day_of_week'   => $a['day_of_week'],
                'start_time'    => $a['start_time'],
                'end_time'      => $a['end_time'],
            ]);
        }
    }

    private function addLocationAvailabilityOverrideToProvider($locationId,$availabilityOverride)
    {
        foreach ($availabilityOverride as $ao){
            AvailabilityOverride::create([
                'provider_id'   => auth()->id(),
                'location_id'   => $locationId,
                'date'          => $ao['date'] ?? null,
                'start_time'    => $ao['start_time'],
                'end_time'      => $ao['end_time'],
            ]);
        }
    }


}
