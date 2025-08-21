<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = User::role('provider')->get();

        foreach ($providers as $provider){
            for($i = 1 ; $i < 3 ; $i++){
                Location::create([
                    'provider_id' => $provider->id,
                    'name' => "Location_$i " . $provider->name,
                    'time_zone' => "Africa/Cairo", // Asia/Riyadh
                ]);
            }

        }
    }
}
