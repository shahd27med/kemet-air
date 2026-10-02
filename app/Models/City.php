<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * A city can have one or more airports.
     */
    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class);
    }
}
