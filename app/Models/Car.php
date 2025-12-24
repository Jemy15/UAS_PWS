<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'make',
        'model',
        'year',
        'price_per_day',
        'available',
    ];

    /**
     * Get the bookings for this car.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
