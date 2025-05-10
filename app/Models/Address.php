<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'landmark',
        'barangay',
        'city',
        'province',
        'region',
        'postal',
        'country',
        'isdefault',
        'type',
    ];

    protected $casts = [
        'isdefault' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
