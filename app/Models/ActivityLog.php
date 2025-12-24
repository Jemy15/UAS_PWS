<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'action',
        'meta',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'meta' => 'json',
        ];
    }

    /**
     * Activity log belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
