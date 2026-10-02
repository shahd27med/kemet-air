<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\City;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            'CAI' => ['name' => 'Cairo International Airport', 'code' => 'CAI'],
            'ALX' => ['name' => 'Borg El Arab International Airport', 'code' => 'ALX'],
            'LXR' => ['name' => 'Luxor International Airport', 'code' => 'LXR'],
            'ASW' => ['name' => 'Aswan International Airport', 'code' => 'ASW'],
            'SSH' => ['name' => 'Sharm El-Sheikh International Airport', 'code' => 'SSH'],
            'HRG' => ['name' => 'Hurghada International Airport', 'code' => 'HRG'],
        ];

        foreach ($airports as $cityCode => $airport) {
            $city = City::where('code', $cityCode)->first();

            if (! $city) {
                continue;
            }

            Airport::updateOrCreate(
                ['code' => $airport['code']],
                [
                    'city_id' => $city->id,
                    'name' => $airport['name'],
                ]
            );
        }
    }
}
