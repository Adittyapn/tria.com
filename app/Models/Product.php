<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    // Pricing type constants for better type safety
    public const PRICING_TYPES = [
        'per_piece' => 'Per Piece',
        'per_meter_square' => 'Per Meter Square',
        'per_meter_linear' => 'Per Meter Linear',
        'fixed_size' => 'Fixed Size',
        'bulk_package' => 'Bulk Package'
    ];

    public const PRODUCT_TYPES = [
        'banner' => 'Banner / Spanduk',
        'poster' => 'Poster',
        'sticker' => 'Stiker',
        'brochure' => 'Brosur',
        'business_card' => 'Kartu Nama',
        'flyer' => 'Flyer',
        'other' => 'Produk Lain'
    ];

    public const PRODUCTION_PRIORITIES = [
        'normal' => 'Normal',
        'express' => 'Express',
        'same_day' => 'Same Day'
    ];

    protected $fillable = [
        // Basic Info
        'name', 'slug', 'sku', 'category_id', 'product_type', 'is_active', 'is_featured',

        // Description & Media
        'short_description', 'description', 'featured_image', 'gallery_images',

        // Pricing System
        'pricing_type', 'unit_label', 'minimum_quantity', 'step_quantity',
        'maximum_quantity', 'allows_custom_size', 'base_price', 'promo_price',

        // Size Presets & Volume Pricing
        'size_presets', 'volume_pricing',

        // Materials & Finishing
        'default_material', 'default_finishing', 'material_options', 'finishing_options',

        // File Upload & Design
        'requires_design_file', 'offers_design_service', 'file_requirements',
        'max_file_size_mb', 'allowed_formats', 'design_service_price',

        // Production & Shipping
        'production_days', 'production_priority', 'estimated_weight_per_unit',
        'requires_approval', 'production_notes',

        // SEO
        'meta_title', 'meta_description', 'keywords',

        // Legacy support
        'stock_quantity', 'stock_status', 'minimum_order', 'weight', 'production_time',
        'requires_design_approval', 'package_length', 'package_width', 'package_height',
        'tags', 'specifications', 'file_upload_notes', 'max_file_size', 'allowed_file_types',
    ];

    protected $casts = [
        // JSON fields
        'gallery_images' => 'array',
        'size_presets' => 'array',
        'volume_pricing' => 'array',
        'material_options' => 'array',
        'finishing_options' => 'array',
        'allowed_formats' => 'array',
        'keywords' => 'array',
        'tags' => 'array',
        'specifications' => 'array',
        'allowed_file_types' => 'array',

        // Boolean fields
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'allows_custom_size' => 'boolean',
        'requires_design_file' => 'boolean',
        'offers_design_service' => 'boolean',
        'requires_approval' => 'boolean',
        'requires_design_approval' => 'boolean',

        // Numeric fields
        'minimum_quantity' => 'integer',
        'step_quantity' => 'integer',
        'maximum_quantity' => 'integer',
        'stock_quantity' => 'integer',
        'minimum_order' => 'integer',
        'weight' => 'integer',
        'production_time' => 'integer',
        'production_days' => 'integer',
        'max_file_size_mb' => 'integer',
        'max_file_size' => 'integer',
        'estimated_weight_per_unit' => 'integer',

        // Price fields
        'base_price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'design_service_price' => 'decimal:2',
        'package_length' => 'decimal:2',
        'package_width' => 'decimal:2',
        'package_height' => 'decimal:2',
    ];

    protected $attributes = [
        'unit_label' => 'pcs',
        'minimum_quantity' => 1,
        'step_quantity' => 1,
        'pricing_type' => 'per_piece',
        'production_priority' => 'normal',
        'is_active' => true,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('product_type', $type);
    }

    public function scopeOnPromo(Builder $query): Builder
    {
        return $query->whereNotNull('promo_price')
                    ->where('promo_price', '<', $this->base_price);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Mutators
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute(): string
    {
        if ($this->featured_image && Storage::disk('public')->exists($this->featured_image)) {
            return Storage::disk('public')->url($this->featured_image);
        }

        return asset('images/default-product.png');
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery_images) {
            return [];
        }

        return collect($this->gallery_images)
            ->map(fn($image) => Storage::disk('public')->exists($image)
                ? Storage::disk('public')->url($image)
                : null
            )
            ->filter()
            ->values()
            ->all();
    }

    public function getFinalPriceAttribute(): float
    {
        return $this->promo_price ?? $this->base_price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->promo_price || $this->promo_price >= $this->base_price) {
            return null;
        }

        return (int) round((($this->base_price - $this->promo_price) / $this->base_price) * 100);
    }

    public function getPricingDescriptionAttribute(): string
    {
        return match ($this->pricing_type) {
            'per_piece' => "Harga per {$this->unit_label}",
            'per_meter_square' => "Harga per m²",
            'per_meter_linear' => "Harga per meter",
            'fixed_size' => "Harga sesuai ukuran tetap",
            'bulk_package' => "Harga paket grosir",
            default => "Harga per {$this->unit_label}"
        };
    }

    public function getEstimatedProductionDateAttribute(): ?\Carbon\Carbon
    {
        if (!$this->production_days) {
            return null;
        }

        // Skip weekends for production days calculation
        $workingDays = 0;
        $currentDate = now();

        while ($workingDays < $this->production_days) {
            $currentDate = $currentDate->addDay();

            // Skip weekends (Saturday = 6, Sunday = 0)
            if (!in_array($currentDate->dayOfWeek, [0, 6])) {
                $workingDays++;
            }
        }

        return $currentDate;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods - MISSING METHODS YANG DIPANGGIL DI CONTROLLER
    |--------------------------------------------------------------------------
    */

    /**
     * Get volume price based on quantity with discount applied
     */
    public function getVolumePrice(int $quantity): float
    {
        $basePrice = $this->final_price;

        if (!$this->volume_pricing) {
            return $basePrice;
        }

        // Find applicable volume tier
        foreach ($this->volume_pricing as $tier) {
            $minQty = $tier['min_qty'] ?? 0;
            $maxQty = $tier['max_qty'] ?? null;

            if ($quantity >= $minQty && ($maxQty === null || $quantity <= $maxQty)) {
                $discountPercent = $tier['discount_percent'] ?? 0;
                return $basePrice * (1 - ($discountPercent / 100));
            }
        }

        return $basePrice;
    }

    /**
     * Calculate volume discount for given quantity and subtotal
     */
    public function getVolumeDiscount(int $quantity, float $subtotal): array
    {
        if (!$this->volume_pricing) {
            return ['discount' => 0, 'percent' => 0];
        }

        foreach ($this->volume_pricing as $tier) {
            $minQty = $tier['min_qty'] ?? 0;
            $maxQty = $tier['max_qty'] ?? null;

            if ($quantity >= $minQty && ($maxQty === null || $quantity <= $maxQty)) {
                $discountPercent = $tier['discount_percent'] ?? 0;
                $discount = ($subtotal * $discountPercent) / 100;

                return [
                    'discount' => $discount,
                    'percent' => $discountPercent,
                    'tier_name' => $tier['label'] ?? "Diskon {$discountPercent}%"
                ];
            }
        }

        return ['discount' => 0, 'percent' => 0];
    }

    public function getProductTypeLabel(): string
    {
        return self::PRODUCT_TYPES[$this->product_type] ??
               ucfirst(str_replace('_', ' ', $this->product_type ?? ''));
    }

    public function getPricingTypeLabel(): string
    {
        return self::PRICING_TYPES[$this->pricing_type] ??
               ucfirst($this->pricing_type);
    }

    public function getProductionPriorityLabel(): string
    {
        return self::PRODUCTION_PRIORITIES[$this->production_priority] ??
               ucfirst($this->production_priority);
    }

    /**
     * Calculate comprehensive price with all options
     */
    public function calculatePrice(array $options = []): array
    {
        $basePrice = $this->final_price;
        $quantity = max(1, (int)($options['quantity'] ?? 1));

        // Volume pricing base
        $unitPrice = $this->getVolumePrice($quantity);

        // Material adjustment
        $materialAdjustment = 0;
        if (!empty($options['material'])) {
            $material = collect($this->material_options)->firstWhere('name', $options['material']);
            $materialAdjustment = $material['price_adjustment'] ?? 0;
        }

        // Finishing adjustments
        $finishingAdjustment = 0;
        if (!empty($options['finishing']) && is_array($options['finishing'])) {
            foreach ($options['finishing'] as $finishingName) {
                $finishing = collect($this->finishing_options)->firstWhere('name', $finishingName);
                $finishingAdjustment += $finishing['price_adjustment'] ?? 0;
            }
        }

        // Size preset multiplier
        $presetMultiplier = 1;
        if (!empty($options['preset'])) {
            $preset = collect($this->size_presets)->firstWhere('name', $options['preset']);
            $presetMultiplier = $preset['price_multiplier'] ?? 1;
        }

        // Custom size calculation
        $sizeMultiplier = 1;
        if ($this->allows_custom_size && !empty($options['custom_size'])) {
            $sizeMultiplier = $this->calculateSizeMultiplier($options['custom_size']);
        }

        // Calculate unit price with all adjustments
        $finalUnitPrice = ($unitPrice + $materialAdjustment + $finishingAdjustment) * $presetMultiplier;

        // Apply size multiplier
        $subtotal = $finalUnitPrice * $sizeMultiplier * $quantity;

        // Volume discount (apply after all calculations)
        $volumeDiscount = $this->getVolumeDiscount($quantity, $subtotal);

        // Design service
        $designServiceCost = 0;
        if (!empty($options['design_service']) && $this->offers_design_service) {
            $designServiceCost = $this->design_service_price;
        }

        $finalTotal = max(0, $subtotal - $volumeDiscount['discount'] + $designServiceCost);

        return [
            'unit_price' => $finalUnitPrice,
            'base_price' => $basePrice,
            'material_adjustment' => $materialAdjustment,
            'finishing_adjustment' => $finishingAdjustment,
            'preset_multiplier' => $presetMultiplier,
            'size_multiplier' => $sizeMultiplier,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'volume_discount' => $volumeDiscount['discount'],
            'volume_discount_percent' => $volumeDiscount['percent'],
            'design_service_cost' => $designServiceCost,
            'final_total' => $finalTotal
        ];
    }

    private function calculateSizeMultiplier(array $size): float
    {
        return match ($this->pricing_type) {
            'per_meter_square' => ($size['length'] ?? 1) * ($size['width'] ?? 1),
            'per_meter_linear' => $size['length'] ?? 1,
            default => 1
        };
    }

    public function isInStock(int $requestedQuantity = 1): bool
    {
        // For custom products, always consider in stock unless specifically disabled
        if ($this->product_type === 'custom') {
            return $this->is_active;
        }

        return $this->is_active &&
               (!$this->stock_quantity || $this->stock_quantity >= $requestedQuantity);
    }

    public function canCustomSize(): bool
    {
        return $this->allows_custom_size &&
               in_array($this->pricing_type, ['per_meter_square', 'per_meter_linear']);
    }

    public function hasVolumeDiscount(): bool
    {
        return !empty($this->volume_pricing);
    }

    public function requiresDesignFile(): bool
    {
        return $this->requires_design_file;
    }

    public function validateCustomSize(array $size): array
    {
        $errors = [];

        if (!$this->canCustomSize()) {
            return $errors;
        }

        $length = $size['length'] ?? 0;
        $width = $size['width'] ?? 0;

        // Minimum size validation
        if ($length < 0.1) {
            $errors['length'] = 'Panjang minimal 0.1 meter';
        }

        if ($this->pricing_type === 'per_meter_square' && $width < 0.1) {
            $errors['width'] = 'Lebar minimal 0.1 meter';
        }

        // Maximum size validation
        if ($length > 50) {
            $errors['length'] = 'Panjang maksimal 50 meter';
        }

        if ($this->pricing_type === 'per_meter_square' && $width > 50) {
            $errors['width'] = 'Lebar maksimal 50 meter';
        }

        return $errors;
    }

    /**
     * Validate file upload requirements
     */
    public function validateDesignFile($file): array
    {
        $errors = [];

        if (!$this->requires_design_file) {
            return $errors;
        }

        if (!$file) {
            $errors['design_file'] = 'File desain wajib diupload';
            return $errors;
        }

        // Check file size
        if ($this->max_file_size_mb) {
            $maxSizeBytes = $this->max_file_size_mb * 1024 * 1024;
            if ($file->getSize() > $maxSizeBytes) {
                $errors['design_file'] = "Ukuran file maksimal {$this->max_file_size_mb}MB";
            }
        }

        // Check file format
        if ($this->allowed_formats && count($this->allowed_formats) > 0) {
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, array_map('strtolower', $this->allowed_formats))) {
                $allowedFormats = implode(', ', $this->allowed_formats);
                $errors['design_file'] = "Format file harus: {$allowedFormats}";
            }
        }

        return $errors;
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Auto-generate SKU if not provided
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(8));
            }
        });

        static::updating(function ($product) {
            // Clear promo price if it's not actually a discount
            if ($product->promo_price && $product->promo_price >= $product->base_price) {
                $product->promo_price = null;
            }
        });
    }
}
