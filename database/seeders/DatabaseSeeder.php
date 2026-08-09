<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Psy\Util\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('password123'),
            'phone'             => '9876543210',
            'profile_image'     => 'default.png',   // or null
            'city'              => 'Ranchi',
            'state'             => 'Jharkhand',
            'country'           => 'India',
            'pincode'           => '834001',
            'address'           => 'Bariatu Road, Ranchi',
            'is_active'         => 1,
            'user_type'         => 'admin',         // or 'user'
            'remember_token'    => '',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
