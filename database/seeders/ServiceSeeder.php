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

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = User::role('provider')->get();

        foreach ($providers as $provider){

            $providerLocations = Location::where('provider_id',$provider->id)->get();

            for($i = 1 ; $i < 3 ; $i++){
                $service = Service::create([
                    'provider_id'   => $provider->id,
                    'name'          => "Service_$i " . $provider->name,
                    'description'   => "Service_$i " . $provider->name . "description",
                    'duration'      => rand(1, 3) * 30,
                    'price'         => rand(1, 3) * 30,
                    'category_id'   => Category::whereId($i)->first() ? $i : Category::first()->id,
                    'is_published'  => true,
                ]);

                foreach ($providerLocations as $providerLocation){
                    LocationService::create([
                        "location_id"   => $providerLocation->id,
                        "service_id"    => $service->id,
                    ]);

                }

            }

        }
    }
}
