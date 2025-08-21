<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
           'name' => "Admin",
           'email' => "admin@demo.com",
           'password' => Hash::make('123456')
        ]);
        $user->assignRole('admin');

        for($i = 1 ; $i < 6 ; $i++){
            $user = User::create([
                'name' => "Provider".$i,
                'email' => "provider$i@demo.com",
                'password' => Hash::make('123456')
            ]);
            $user->assignRole('provider');
        }

        for($i = 1 ; $i < 6 ; $i++){
            $user = User::create([
                'name' => "Customer$i",
                'email' => "customer$i@demo.com",
                'password' => Hash::make('123456')
            ]);
            $user->assignRole('customer');
        }
    }
}
