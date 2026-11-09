<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
        'is_active',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_per_hour' => 'integer',
    ];

    /**
     * Relasi: Satu lapangan memiliki banyak booking.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class);
}

public function getAverageRatingAttribute()
{
    return round($this->reviews()->avg('rating') ?: 5.0, 1);
}

public function getTotalReviewsAttribute()
{
    return $this->reviews()->count();
}
}
