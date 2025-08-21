<?php

namespace Database\Seeders;

use App\Enums\DayOfWeek;
use App\Models\Availability;
use App\Models\AvailabilityOverride;
use App\Models\Category;
use App\Models\Location;
use App\Models\LocationService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProviderAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = User::role('provider')->get();

        foreach ($providers as $provider){

            $providerLocations = Location::where('provider_id',$provider->id)->get();

            foreach ($providerLocations as $providerLocation) {

                //availability of provider in locations
                foreach (DayOfWeek::getValues() as $day) {
                    Availability::create([
                        'provider_id' => $provider->id,
                        'location_id' => $providerLocation->id,
                        'day_of_week' => $day, // Monday, Friday
                        'start_time'  => '10:00:00',
                        'end_time'    => '14:00:00',
                    ]);
                }

                // Add an override: block specific date
                AvailabilityOverride::create([
                    'provider_id' => $provider->id,
                    'location_id' => $providerLocation->id,
                    'date'        => now()->addWeek()->startOfWeek()->addDays(2)->toDateString(),
                    'start_time'  => '10:00:00',
                    'end_time'    => '12:00:00',
                ]);

            }




        }
    }
}
