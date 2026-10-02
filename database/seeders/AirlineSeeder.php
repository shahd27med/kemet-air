<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    public function run(): void
    {
        $airlines = [
            ['name' => 'EgyptAir', 'code' => 'MS', 'logo' => 'airlines/egyptair.png'],
            ['name' => 'Air Cairo', 'code' => 'SM', 'logo' => 'airlines/aircairo.png'],
            ['name' => 'Nile Air', 'code' => 'NP', 'logo' => 'airlines/nileair.png'],
        ];

        foreach ($airlines as $airline) {
            Airline::updateOrCreate(['code' => $airline['code']], $airline);
        }
    }
}
