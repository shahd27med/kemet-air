<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@flightbooking.eg'],
            [
                'name' => 'System Admin',
                'phone' => '+201000000000',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $customers = [
            ['name' => 'Ahmed Mostafa', 'email' => 'ahmed.mostafa@example.com', 'phone' => '+201012345671'],
            ['name' => 'Mona Youssef', 'email' => 'mona.youssef@example.com', 'phone' => '+201012345672'],
            ['name' => 'Karim Adel', 'email' => 'karim.adel@example.com', 'phone' => '+201012345673'],
            ['name' => 'Sara Ibrahim', 'email' => 'sara.ibrahim@example.com', 'phone' => '+201012345674'],
            ['name' => 'Omar Hassan', 'email' => 'omar.hassan@example.com', 'phone' => '+201012345675'],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'phone' => $customer['phone'],
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
