<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountSetting extends Model
{
    protected $fillable = [
        'min_passengers',
        'max_passengers',
        'discount_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Resolve the applicable discount percentage for a given passenger count.
     * Returns 0.0 when no active tier matches (e.g. 1-2 passengers).
     */
    public static function getDiscountForPassengers(int $passengerCount): float
    {
        $tier = self::query()
            ->where('is_active', true)
            ->where('min_passengers', '<=', $passengerCount)
            ->where(function ($query) use ($passengerCount) {
                $query->whereNull('max_passengers')
                    ->orWhere('max_passengers', '>=', $passengerCount);
            })
            ->orderByDesc('discount_percentage')
            ->first();

        return $tier ? (float) $tier->discount_percentage : 0.0;
    }
}
