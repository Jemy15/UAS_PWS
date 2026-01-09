<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    // Field yang bisa diisi massal (mass assignable)
    protected $fillable = [
        'booking_id',
        'amount',
        'method',
        'status',
    ];

    // Relasi ke model Booking (many-to-one)
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
