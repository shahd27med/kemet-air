<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'user_id',
        'flight_id',
        'total_passengers',
        'subtotal',
        'discount_amount',
        'final_price',
        'payment_status',
        'booking_status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
    }

    /**
     * Generate a unique, human-friendly booking reference, e.g. BK7F3A29X1.
     */
    public static function generateBookingNumber(): string
    {
        do {
            $number = 'BK' . strtoupper(Str::random(8));
        } while (self::where('booking_number', $number)->exists());

        return $number;
    }

    /**
     * Calculate subtotal, discount and final price for a given flight price
     * and passenger count, using the active discount tiers.
     *
     * @return array{subtotal: float, discount_percentage: float, discount_amount: float, final_price: float}
     */
    public static function calculatePricing(float $pricePerSeat, int $passengerCount): array
    {
        $subtotal = round($pricePerSeat * $passengerCount, 2);
        $discountPercentage = DiscountSetting::getDiscountForPassengers($passengerCount);
        $discountAmount = round($subtotal * ($discountPercentage / 100), 2);
        $finalPrice = round($subtotal - $discountAmount, 2);

        return [
            'subtotal' => $subtotal,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
