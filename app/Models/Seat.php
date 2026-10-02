<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seat extends Model
{
    protected $fillable = [
        'flight_id',
        'seat_number',
        'seat_class',
        'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'is_booked' => 'boolean',
        ];
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    /**
     * The passenger currently assigned to this seat, if any.
     */
    public function passenger(): HasOne
    {
        return $this->hasOne(Passenger::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_booked', false);
    }

    public function scopeOfClass(Builder $query, string $class): Builder
    {
        return $query->where('seat_class', $class);
    }
}
