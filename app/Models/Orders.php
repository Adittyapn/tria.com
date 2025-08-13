<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'name',
        'email',
        'phone',
        'address',
        'notes',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Payments
    public function payment()
    {
        return $this->hasOne(Payments::class);
    }
    public function items()
    {
        return $this->hasMany(\App\Models\OrderItems::class, 'order_id');
    }
    public function updateTotalAmount()
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }

}
