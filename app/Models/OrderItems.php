<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property string $price
 * @property string $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Orders $order
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItems whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
