<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected static function booted()
    {
        static::saved(function ($item) {
            $item->order->updateTotalAmount();
        });

        static::deleted(function ($item) {
            $item->order->updateTotalAmount();
        });
    }

}
