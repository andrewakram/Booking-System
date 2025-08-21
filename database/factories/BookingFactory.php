<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $start = Carbon::today()->addDay()->setTime(10, 0, 0);
        $end   = (clone $start)->addMinutes(60);

        $provider = User::factory()->create();
        $service  = Service::factory()->create(['provider_id' => $provider->id]);

        return [
            'customer_id'     => User::factory(),
            'provider_id' => $provider->id,
            'service_id'  => $service->id,
            'location_id' => 1,
            'date'        => $start->toDateString(),
            'start_time'  => $start->format('H:i:s'),
            'end_time'    => $end->format('H:i:s'),
            'status'      => 'pending',
            'price'       => 20.00,
        ];
    }
}
