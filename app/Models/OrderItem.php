<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;


// ========================================
// Updated OrderItem Model
// ========================================

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items'; // Sesuai dengan existing table

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
    'price', // ← ganti dari 'unit_price' ke 'price'
        'subtotal',
        'custom_size_width',
        'custom_size_height',
        'selected_material',
        'selected_finishing',
        'design_notes',
        'design_file_path',
        'requires_design_service',
    ];

    protected $casts = [
        'requires_design_service' => 'boolean',
    'price' => 'decimal:2', // ← ganti dari 'unit_price'
        'subtotal' => 'decimal:2',
        'custom_size_width' => 'decimal:2',
        'custom_size_height' => 'decimal:2',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->unit_price * $this->quantity;
        $this->save();
    }

    public function getCustomSizeAttribute(): ?string
    {
        if ($this->custom_size_width && $this->custom_size_height) {
            return $this->custom_size_width . ' x ' . $this->custom_size_height . ' cm';
        }
        return null;
    }

    public function hasDesignFile(): bool
    {
        return !empty($this->design_file_path) && file_exists(storage_path('app/public/' . $this->design_file_path));
    }

  public static function createFromCart(Cart $cartItem, $orderId): self
    {
        $permanentPath = null;
        $tempPath = $cartItem->design_file_path;

        // [FIX] Gunakan Storage Facade untuk memindahkan file
        if (!empty($tempPath) && Storage::disk('public')->exists($tempPath)) {
            $order = Order::find($orderId);
            $filename = basename($tempPath);
            $permanentPath = "designs/orders/{$order->order_number}/{$filename}";

            Storage::disk('public')->move($tempPath, $permanentPath);
        }

        return self::create([
            'order_id' => $orderId,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
    'price' => $cartItem->unit_price, // ← ganti field name
            'subtotal' => $cartItem->subtotal,
            'custom_size_width' => $cartItem->custom_size_width,
            'custom_size_height' => $cartItem->custom_size_height,
            'selected_material' => $cartItem->selected_material,
            'selected_finishing' => $cartItem->selected_finishing,
            'design_notes' => $cartItem->design_notes,
            'design_file_path' => $permanentPath,
            'requires_design_service' => $cartItem->requires_design_service,
        ]);
    }
}
