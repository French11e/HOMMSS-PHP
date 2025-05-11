<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'name',
        'phone',
        'postal',
        'barangay',
        'city',
        'province',
        'region',
        'address',
        'landmark',
        'status',
        'processing_date',
        'shipped_date',
        'delivered_date',
        'canceled_date'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'processing_date',
        'shipped_date',
        'delivered_date',
        'canceled_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}

