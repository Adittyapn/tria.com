<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'order_number',
        'tracking_token',                    // ✅ NEW: Secure tracking token
        'tracking_token_expires_at',         // ✅ NEW: Token expiry
        'subtotal_items',
        'shipping_cost',
        'tax_amount',
        'total_amount',
        'status',
        'payment_status',
        'payment_proof',
        'notes',

        // Shipping details with district support
        'shipping_province_id',
        'shipping_city_id',
        'shipping_district_id',
        'shipping_province_name',
        'shipping_city_name',
        'shipping_district_name',
        'shipping_address',
        'shipping_courier',
        'shipping_service',
        'shipping_etd',
        'tracking_number',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal_items' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_province_id' => 'integer',
        'shipping_city_id' => 'integer',
        'shipping_district_id' => 'integer',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'tracking_token_expires_at' => 'datetime',    // ✅ NEW: Cast token expiry
    ];

    // Constants
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_READY = 'ready';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_PENDING  = 'pending';
    const PAYMENT_STATUS_PAID = 'verified';
    const PAYMENT_STATUS_FAILED = 'rejected';

    // ===============================================
    // 🔐 TRACKING SECURITY METHODS
    // ===============================================

    /**
     * ✅ Generate cryptographically secure tracking token
     */
    public static function generateTrackingToken(): string
    {
        return hash('sha256',
            uniqid('track_', true) .
            time() .
            Str::random(32) .
            config('app.key')
        );
    }

    /**
     * ✅ Generate and assign tracking token to order
     */
    public function assignTrackingToken(): void
    {
        if (!$this->tracking_token) {
            $this->tracking_token = self::generateTrackingToken();
            // Token expires when order completed (no expiry date set initially)
            $this->save();
        }
    }

    /**
     * ✅ Validate tracking token
     */
    public function isValidTrackingToken(string $token): bool
    {
        // Check if token matches
        if ($this->tracking_token !== $token) {
            return false;
        }

        // Check if token expired (only when order is completed)
        if ($this->tracking_token_expires_at && now()->gt($this->tracking_token_expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * ✅ Check if order has secure tracking
     */
    public function hasSecureTracking(): bool
    {
        return !empty($this->tracking_token);
    }

    /**
     * ✅ Get secure tracking URL
     */
    public function getSecureTrackingUrl(): string
    {
        if (!$this->hasSecureTracking()) {
            return route('orders.track.verify', $this->order_number);
        }

        return route('orders.track.secure', [
            'orderNumber' => $this->order_number,
            'token' => $this->tracking_token
        ]);
    }

    /**
     * ✅ Expire tracking token when order completed
     */
    public function expireTrackingToken(): void
    {
        if ($this->hasSecureTracking()) {
            $this->update([
                'tracking_token_expires_at' => now()
            ]);
        }
    }

    // ===============================================
    // RELATIONSHIPS
    // ===============================================

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // ===============================================
    // EXISTING METHODS (Updated with security)
    // ===============================================

    public function updateTotalAmount(): void
    {
        $subtotal = $this->items()->sum('subtotal');
        $tax = $subtotal * 0.11; // 11% PPN

        $this->subtotal_items = $subtotal;
        $this->tax_amount = $tax;
        $this->total_amount = $subtotal + $this->shipping_cost + $tax;
        $this->save();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_PAID => 'Sudah Dibayar',
            self::STATUS_PROCESSING => 'Sedang Produksi',
            self::STATUS_READY => 'Siap Kirim',
            self::STATUS_SHIPPED => 'Dalam Pengiriman',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            self::PAYMENT_STATUS_PENDING  => 'Menunggu Verifikasi',
            self::PAYMENT_STATUS_PAID => 'Terverifikasi',
            self::PAYMENT_STATUS_FAILED => 'Ditolak',
            default => 'Unknown',
        };
    }

    public function getFullShippingAddressAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_address,
            $this->shipping_district_name,
            $this->shipping_city_name,
            $this->shipping_province_name
        ]);

        return implode(', ', $parts);
    }

    public function getShippingLocationAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_district_name,
            $this->shipping_city_name,
            $this->shipping_province_name
        ]);

        return implode(', ', $parts);
    }

    public function getShippingServiceDisplayAttribute(): string
    {
        if (!$this->shipping_service || !$this->shipping_courier) {
            return 'Layanan Reguler';
        }

        return strtoupper($this->shipping_courier) . ' ' . $this->shipping_service;
    }

    public function canBeTracked(): bool
    {
        return !empty($this->tracking_number) &&
               !empty($this->shipping_courier) &&
               in_array($this->status, [self::STATUS_SHIPPED, self::STATUS_COMPLETED]);
    }

    /**
     * ✅ UPDATED: Mark as shipped
     */
    public function markAsShipped(string $trackingNumber): void
    {
        $this->update([
            'status' => self::STATUS_SHIPPED,
            'tracking_number' => $trackingNumber,
            'shipped_at' => now()
        ]);
    }

    /**
     * ✅ UPDATED: Mark as delivered and expire tracking token
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'delivered_at' => now()
        ]);

        // ✅ Expire tracking token when order completed
        $this->expireTrackingToken();
    }

    /**
     * ✅ UPDATED: Mark as completed and expire tracking token
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
        $this->expireTrackingToken();
    }

    public function getShippingDistrictIdAttribute(): ?int
    {
        return $this->shipping_district_id ?? $this->shipping_city_id;
    }

    public function hasDistrictLevelShipping(): bool
    {
        return !empty($this->shipping_district_id) && !empty($this->shipping_district_name);
    }

    public static function generateOrderNumber(): string
    {
        $date = date('Ymd');
        $lastOrder = self::where('order_number', 'like', "DP-{$date}-%")
                        ->orderBy('order_number', 'desc')
                        ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "DP-{$date}-{$newNumber}";
    }
}
