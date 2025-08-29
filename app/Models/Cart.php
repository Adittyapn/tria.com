<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'unit_price',
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
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'custom_size_width' => 'decimal:2',
        'custom_size_height' => 'decimal:2',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return !empty($this->design_file_path) && Storage::disk('public')->exists($this->design_file_path);
    }

    // Static methods untuk cart operations
    public static function getCartItems($userId = null, $sessionId = null)
    {
        $query = self::with(['product', 'product.category']);

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public static function getTotalAmount($userId = null, $sessionId = null): float
    {
        $query = self::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->sum('subtotal');
    }

    public static function getItemCount($userId = null, $sessionId = null): int
    {
        $query = self::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->sum('quantity');
    }

    // FIXED: Improved file cleanup with Storage facade
    public static function clearCart($userId = null, $sessionId = null): void
    {
        $items = self::getCartItems($userId, $sessionId);

        // Delete temporary design files safely
        foreach ($items as $item) {
            if ($item->design_file_path && Storage::disk('public')->exists($item->design_file_path)) {
                try {
                    Storage::disk('public')->delete($item->design_file_path);
                } catch (\Exception $e) {
                    \Log::warning("Failed to delete cart design file: {$item->design_file_path}. Error: " . $e->getMessage());
                }
            }
        }

        // Delete cart items
        $query = self::query();
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        $query->delete();
    }
}
