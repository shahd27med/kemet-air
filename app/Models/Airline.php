<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Airline extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'code',
    ];

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }

    /**
     * Accessor for a full, browser-usable logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}
