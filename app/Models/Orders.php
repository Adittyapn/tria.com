<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $order_number
 * @property string $total_amount
 * @property string $status
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $address
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItems> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Payments|null $payment
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Orders whereUserId($value)
 *
 * @mixin \Eloquent
 */
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
