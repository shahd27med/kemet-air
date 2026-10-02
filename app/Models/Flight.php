<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    /**
     * The schema stores a single base `price` per flight rather than a
     * separate fare per cabin. To let search results show a distinct
     * Business fare without altering the Step 1 schema, Business is priced
     * as a multiplier over the base (economy) price at the application
     * layer. Adjust this if real per-class pricing is added later.
     */
    public const CLASS_PRICE_MULTIPLIERS = [
        'economy' => 1.0,
        'business' => 1.6,
    ];

    protected $fillable = [
        'airline_id',
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_time',
        'arrival_time',
        'price',
        'total_seats',
        'available_seats',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    public function airline(): BelongsTo
    {
        return $this->belongsTo(Airline::class);
    }

    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Seats on this flight that are still free.
     */
    public function availableSeatsQuery(): HasMany
    {
        return $this->seats()->where('is_booked', false);
    }

    public function economySeats(): HasMany
    {
        return $this->seats()->where('seat_class', 'economy');
    }

    public function businessSeats(): HasMany
    {
        return $this->seats()->where('seat_class', 'business');
    }

    /**
     * Flight duration in whole minutes.
     */
    public function getDurationInMinutesAttribute(): int
    {
        return $this->departure_time->diffInMinutes($this->arrival_time);
    }

    /**
     * Flight duration formatted as "2h 15m".
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = $this->duration_in_minutes;

        return floor($minutes / 60) . 'h ' . str_pad((string) ($minutes % 60), 2, '0', STR_PAD_LEFT) . 'm';
    }

    /**
     * Fare for a given cabin class (see CLASS_PRICE_MULTIPLIERS).
     */
    public function priceForClass(string $seatClass): float
    {
        $multiplier = self::CLASS_PRICE_MULTIPLIERS[$seatClass] ?? 1.0;

        return round((float) $this->price * $multiplier, 2);
    }

    /**
     * How many unbooked seats remain in a given cabin class.
     */
    public function availableSeatsForClass(string $seatClass): int
    {
        return $this->seats()
            ->where('seat_class', $seatClass)
            ->where('is_booked', false)
            ->count();
    }

    /**
     * How many unbooked seats remain across all cabin classes.
     */
    public function totalAvailableSeats(): int
    {
        return $this->seats()->where('is_booked', false)->count();
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('departure_time', '>', now());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['scheduled', 'delayed']);
    }

    /**
     * Filter flights between two specific airports.
     */
    public function scopeBetweenAirports(Builder $query, int $departureAirportId, int $arrivalAirportId): Builder
    {
        return $query->where('departure_airport_id', $departureAirportId)
            ->where('arrival_airport_id', $arrivalAirportId);
    }

    /**
     * Decrement available_seats safely (used when a booking is confirmed).
     */
    public function decrementAvailableSeats(int $count = 1): void
    {
        $this->decrement('available_seats', $count);
    }

    /**
     * Increment available_seats safely (used when a booking is cancelled).
     */
    public function incrementAvailableSeats(int $count = 1): void
    {
        $this->increment('available_seats', $count);
    }

    /**
     * Generate this flight's seat map: the first 2 rows are Business class,
     * the rest Economy, 4 seats per row (A–D) — the same convention
     * FlightSeeder uses. $totalSeats must be a multiple of 4. No-ops if
     * seats already exist, so it's safe to call more than once.
     */
    public function generateSeatMap(int $totalSeats, int $businessRows = 2): void
    {
        if ($this->seats()->exists()) {
            return;
        }

        $letters = ['A', 'B', 'C', 'D'];
        $totalRows = (int) ceil($totalSeats / count($letters));

        $seatsToInsert = [];
        for ($row = 1; $row <= $totalRows; $row++) {
            $class = $row <= $businessRows ? 'business' : 'economy';

            foreach ($letters as $letter) {
                $seatsToInsert[] = [
                    'flight_id' => $this->id,
                    'seat_number' => $letter . $row,
                    'seat_class' => $class,
                    'is_booked' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seatsToInsert);
    }
}
