<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'category_id',
        'is_active',
        'short_description',
        'description',
        'featured_image',
        'gallery_images',
        'price',
        'sale_price',
        'stock_quantity',
        'minimum_order', // Keep existing for compatibility
        'stock_status',
        'specifications',
        'size_variants',
        'file_upload_notes',
        'max_file_size',
        'allowed_file_types',
        'weight',
        'production_time',
        'requires_design_approval',
        'package_length',
        'package_width',
        'package_height',
        'meta_title',
        'meta_description',
        'tags',
        'is_featured',

        // Enhanced pricing flexibility fields
            'pricing_type', // 'per_piece', 'per_meter_square', 'per_meter_linear', 'bulk_tier'
            'unit_label', // 'pcs', 'm²', 'meter', '
        ];

    protected $casts = [
        'gallery_images'      => 'array',
        'specifications'      => 'array',
        'size_variants'       => 'array',
        'size_presets'        => 'array',
        'quantity_tiers'      => 'array',
        'allowed_file_types'  => 'array',
        'tags'                => 'array',
        'is_active'           => 'boolean',
        'is_featured'         => 'boolean',
        'requires_design_approval' => 'boolean',
        'has_custom_size'     => 'boolean',
        'has_size_presets'    => 'boolean',
        'minimum_quantity'    => 'integer',
        'maximum_quantity'    => 'integer',
        'step_quantity'       => 'integer',
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor untuk gambar utama
    public function getImageUrlAttribute()
    {
        return $this->featured_image
            ? asset('storage/' . $this->featured_image)
            : asset('images/default-product.png');
    }

    // Harga final berdasarkan quantity dan ukuran
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    // Get unit label yang tepat
    public function getUnitLabelAttribute()
    {
        return $this->attributes['unit_label'] ?? 'pcs';
    }

    // Check if product uses custom sizing
    public function usesCustomSize()
    {
        return $this->has_custom_size && in_array($this->pricing_type, ['per_meter_square', 'per_meter_linear']);
    }

    // Check if product uses quantity tiers
    public function usesQuantityTiers()
    {
        return !empty($this->quantity_tiers) || $this->pricing_type === 'bulk_tier';
    }

    // Get price based on quantity and size
    public function calculatePrice($quantity = 1, $customSize = null)
    {
        $basePrice = $this->sale_price ?? $this->price;

        switch ($this->pricing_type) {
            case 'per_piece':
                return $this->calculatePiecePrice($quantity, $basePrice);

            case 'per_meter_square':
                if ($customSize && isset($customSize['length'], $customSize['width'])) {
                    $area = $customSize['length'] * $customSize['width'];
                    return $this->calculateAreaPrice($quantity, $area, $basePrice);
                }
                return $basePrice * $quantity;

            case 'per_meter_linear':
                if ($customSize && isset($customSize['length'])) {
                    return $this->calculateLinearPrice($quantity, $customSize['length'], $basePrice);
                }
                return $basePrice * $quantity;

            case 'bulk_tier':
                return $this->calculateBulkPrice($quantity, $basePrice);

            default:
                return $basePrice * $quantity;
        }
    }

    // Calculate price per piece with quantity discounts
    protected function calculatePiecePrice($quantity, $basePrice)
    {
        if (empty($this->quantity_tiers)) {
            return $basePrice * $quantity;
        }

        $unitPrice = $basePrice;

        foreach ($this->quantity_tiers as $tier) {
            if ($quantity >= ($tier['min_quantity'] ?? 0)) {
                $unitPrice = $tier['price'] ?? $basePrice;
            }
        }

        return $unitPrice * $quantity;
    }

    // Calculate price for area-based products (banner, spanduk, dll)
    protected function calculateAreaPrice($quantity, $area, $basePrice)
    {
        $pricePerMeter = $basePrice;

        // Cek apakah ada tier harga berdasarkan area
        if (!empty($this->size_variants)) {
            foreach ($this->size_variants as $variant) {
                if (!($variant['is_available'] ?? true)) continue;

                $range = str_replace([' ', 'm', '²'], '', strtolower($variant['size']));

                if (strpos($range, '-') !== false) {
                    [$min, $max] = explode('-', $range);
                    if ($area >= (float)$min && $area <= (float)$max) {
                        $pricePerMeter = $variant['price'] ?? $basePrice;
                        break;
                    }
                } else {
                    if ($area <= (float)$range) {
                        $pricePerMeter = $variant['price'] ?? $basePrice;
                        break;
                    }
                }
            }
        }

        $singleItemPrice = $area * $pricePerMeter;
        return $singleItemPrice * $quantity;
    }

    // Calculate price for linear products
    protected function calculateLinearPrice($quantity, $length, $basePrice)
    {
        return $basePrice * $length * $quantity;
    }

    // Calculate bulk pricing
    protected function calculateBulkPrice($quantity, $basePrice)
    {
        if (empty($this->quantity_tiers)) {
            return $basePrice * $quantity;
        }

        $applicableTier = null;
        foreach ($this->quantity_tiers as $tier) {
            if ($quantity >= ($tier['min_quantity'] ?? 0)) {
                $applicableTier = $tier;
            }
        }

        if ($applicableTier) {
            return ($applicableTier['price'] ?? $basePrice) * $quantity;
        }

        return $basePrice * $quantity;
    }

    // Get available quantity options based on step_quantity
    public function getQuantityOptions($maxOptions = 10)
    {
        $options = [];
        $step = $this->step_quantity ?? 1;
        $min = $this->minimum_quantity ?? 1;
        $max = $this->maximum_quantity ?? ($min + ($step * $maxOptions));

        for ($i = $min; $i <= $max && count($options) < $maxOptions; $i += $step) {
            $options[] = $i;
        }

        return $options;
    }

    // Get size presets for the product
    public function getSizePresets()
    {
        if (!$this->has_size_presets || empty($this->size_presets)) {
            return [];
        }

        return array_filter($this->size_presets, function($preset) {
            return ($preset['is_available'] ?? true);
        });
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }

            // Set default values based on category or product type
            if (empty($product->pricing_type)) {
                $product->pricing_type = 'per_piece'; // default
            }

            if (empty($product->unit_label)) {
                $product->unit_label = 'pcs'; // default
            }

            if (empty($product->minimum_quantity)) {
                $product->minimum_quantity = 1;
            }
        });
    }

    // Helper method to determine if product needs custom input
    public function needsCustomInput()
    {
        return $this->usesCustomSize() || !empty($this->size_presets);
    }

    // Get pricing description for display
    public function getPricingDescriptionAttribute()
    {
        switch ($this->pricing_type) {
            case 'per_piece':
                if ($this->usesQuantityTiers()) {
                    return "Harga per {$this->unit_label} (ada diskon kuantitas)";
                }
                return "Harga per {$this->unit_label}";

            case 'per_meter_square':
                return "Harga per m²";

            case 'per_meter_linear':
                return "Harga per meter";

            case 'bulk_tier':
                return "Harga grosir (minimum {$this->minimum_quantity} {$this->unit_label})";

            default:
                return "Harga per {$this->unit_label}";
        }
    }
}
