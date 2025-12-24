<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin / user
    ];

    /**
     * Kolom yang disembunyikan di response JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* =====================================================
     | JWT IMPLEMENTATION
     |=====================================================*/

    /**
     * Mengambil identifier JWT (biasanya user_id)
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Custom claim JWT (opsional)
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /* =====================================================
     | RELATIONSHIP
     |=====================================================*/

    /**
     * Relasi ke tabel bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi ke tabel activity_logs
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
