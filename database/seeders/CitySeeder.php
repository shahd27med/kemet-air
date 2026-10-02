<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Cairo', 'code' => 'CAI'],
            ['name' => 'Alexandria', 'code' => 'ALX'],
            ['name' => 'Luxor', 'code' => 'LXR'],
            ['name' => 'Aswan', 'code' => 'ASW'],
            ['name' => 'Sharm El-Sheikh', 'code' => 'SSH'],
            ['name' => 'Hurghada', 'code' => 'HRG'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(['code' => $city['code']], $city);
        }
    }
}
