<?php

namespace Database\Seeders;

use App\Models\DiscountSetting;
use Illuminate\Database\Seeder;

class DiscountSettingSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            ['min_passengers' => 1, 'max_passengers' => 2, 'discount_percentage' => 0.00, 'is_active' => true],
            ['min_passengers' => 3, 'max_passengers' => 4, 'discount_percentage' => 5.00, 'is_active' => true],
            ['min_passengers' => 5, 'max_passengers' => 6, 'discount_percentage' => 10.00, 'is_active' => true],
            ['min_passengers' => 7, 'max_passengers' => null, 'discount_percentage' => 15.00, 'is_active' => true],
        ];

        foreach ($tiers as $tier) {
            DiscountSetting::updateOrCreate(
                ['min_passengers' => $tier['min_passengers'], 'max_passengers' => $tier['max_passengers']],
                $tier
            );
        }
    }
}
