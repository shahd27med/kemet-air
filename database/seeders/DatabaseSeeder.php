<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters: reference/lookup tables first, then dependents.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CitySeeder::class,
            AirportSeeder::class,
            AirlineSeeder::class,
            DiscountSettingSeeder::class,
            FlightSeeder::class,   // also generates the seat map per flight
            BookingSeeder::class,  // demo bookings + passengers + payments
        ]);
    }
}
