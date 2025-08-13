<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_proof',
        'payment_status',
        'verified_by',
        'verification_notes',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Orders::class,'order_id');
    }

    // Relasi ke User yang memverifikasi
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
