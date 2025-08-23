<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property string $amount
 * @property string|null $payment_proof
 * @property string $payment_status
 * @property int|null $verified_by
 * @property string|null $verification_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Orders $order
 * @property-read \App\Models\User|null $verifiedBy
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments wherePaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereVerificationNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payments whereVerifiedBy($value)
 *
 * @mixin \Eloquent
 */
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
        return $this->belongsTo(Orders::class, 'order_id');
    }

    // Relasi ke User yang memverifikasi
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
