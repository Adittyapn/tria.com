<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationGroup = 'Produk & Layanan';

    protected static ?string $navigationLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->description('Data dasar produk digital printing')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                        $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    )
                                    ->placeholder('Contoh: Banner Vinyl Outdoor, Stiker Cutting, X-Banner'),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Product::class, 'slug', ignoreRecord: true)
                                    ->helperText('URL untuk produk di website'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ]),

                                Forms\Components\TextInput::make('sku')
                                    ->label('Kode Produk')
                                    ->maxLength(50)
                                    ->unique(Product::class, 'sku', ignoreRecord: true)
                                    ->placeholder('BNR-001, STK-002')
                                    ->helperText('Kosongkan untuk generate otomatis'),

                                Forms\Components\TextInput::make('stock_quantity')
                                    ->label('Stock Quantity')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required(),
                                Forms\Components\Select::make('stock_status')
                                    ->label('Status Stock ')
                                    ->options([
                                        'in_stock' => 'Tersedia',
                                        'out_of_stock' => 'Habis',
                                        'on_backorder' => 'Pre - Order',
                                    ])
                                    ->required()
                                    ->live()
                                    ->searchable(),

                                Forms\Components\Select::make('product_type')
                                    ->label('Jenis Produk')
                                    ->options([
                                        'banner' => '🏴 Banner & Spanduk',
                                        'sticker' => '🏷️ Stiker & Decal',
                                        'backdrop' => '🎭 Backdrop & Photobooth',
                                        'roll_banner' => '📜 Roll Banner (X-Banner, Y-Banner)',
                                        'signage' => '🪧 Papan Nama & Signage',
                                        'merchandise' => '🎁 Merchandise Custom',
                                        'packaging' => '📦 Kemasan Custom',
                                        'display' => '🖼️ Display & Promosi',
                                    ])
                                    ->required()
                                    ->live()
                                    ->searchable(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Produk Unggulan')
                                    ->default(false)
                                    ->helperText('Tampil di homepage & katalog utama'),
                            ]),
                    ]),

                Section::make('Deskripsi & Media')
                    ->schema([
                        Forms\Components\Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Ringkasan produk untuk listing (max 300 karakter)')
                            ->placeholder('Contoh: Banner outdoor berkualitas tinggi, tahan cuaca, cocok untuk promosi event dan bisnis'),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'bulletList', 'orderedList', 'link'
                            ])
                            ->placeholder('Jelaskan detail produk: bahan, kualitas, kegunaan, dll'),

                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama Produk')
                            ->image()
                            ->directory('products/featured')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                            ->required()
                            ->helperText('Gambar utama yang akan muncul di listing'),

                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Galeri Gambar')
                            ->image()
                            ->directory('products/gallery')
                            ->multiple()
                            ->reorderable()
                            ->imageEditor()
                            ->maxFiles(8)
                            ->helperText('Foto contoh hasil, mockup, dan detail produk'),
                    ])
                    ->columns(1),

                Section::make('Sistem Harga & Penjualan')
                    ->description('Tentukan bagaimana produk ini dijual dan dihitung harganya')
                    ->schema([
                        Forms\Components\Select::make('pricing_type')
                            ->label('Sistem Perhitungan Harga')
                            ->options([
                                'per_piece' => '📦 Per Piece/Satuan (Stiker, Pin, Gantungan Kunci)',
                                'per_meter_square' => '📐 Per Meter Persegi (Banner, Spanduk, Backdrop)',
                                'per_meter_linear' => '📏 Per Meter Linear (Banner Panjang, Stiker Roll)',
                                'fixed_size' => '📋 Ukuran Tetap (X-Banner, Roll Banner)',
                                'bulk_package' => '📦 Paket Bulk/Grosir',
                            ])
                            ->default('per_piece')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                match ($state) {
                                    'per_piece' => [
                                        $set('unit_label', 'pcs'),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1),
                                        $set('allows_custom_size', false),
                                    ],
                                    'per_meter_square' => [
                                        $set('unit_label', 'm²'),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1),
                                        $set('allows_custom_size', true),
                                    ],
                                    'per_meter_linear' => [
                                        $set('unit_label', 'meter'),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1),
                                        $set('allows_custom_size', true),
                                    ],
                                    'fixed_size' => [
                                        $set('unit_label', 'pcs'),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1),
                                        $set('allows_custom_size', false),
                                    ],
                                    'bulk_package' => [
                                        $set('unit_label', 'paket'),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1),
                                        $set('allows_custom_size', false),
                                    ],
                                    default => null,
                                };
                            }),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('unit_label')
                                    ->label('Satuan')
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder('pcs, m², meter, set'),

                                Forms\Components\TextInput::make('minimum_quantity')
                                    ->label('Minimum Order')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1),

                                Forms\Components\TextInput::make('step_quantity')
                                    ->label('Kelipatan Pemesanan')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->helperText('Misal: 10 untuk stiker, 1 untuk banner')
                                    ->minValue(1),
                            ]),

                        Forms\Components\Toggle::make('allows_custom_size')
                            ->label('Bisa Pesan Ukuran Custom')
                            ->helperText('Customer dapat input ukuran sesuai kebutuhan'),
                    ]),

                Section::make('Harga Dasar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('base_price')
                                    ->label(function (Get $get) {
                                        $type = $get('pricing_type');
                                        return match ($type) {
                                            'per_meter_square' => 'Harga per m² (Rp)',
                                            'per_meter_linear' => 'Harga per meter (Rp)',
                                            'bulk_package' => 'Harga per paket (Rp)',
                                            'fixed_size' => 'Harga tetap (Rp)',
                                            default => 'Harga per ' . ($get('unit_label') ?: 'pcs') . ' (Rp)',
                                        };
                                    })
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->placeholder('25000'),

                                Forms\Components\TextInput::make('promo_price')
                                    ->label('Harga Promo (opsional)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('20000')
                                    ->helperText('Harga khusus/diskon'),
                            ]),
                    ]),

                Section::make('Preset Ukuran Standar')
                    ->description('Ukuran-ukuran yang sering dipesan customer')
                    ->schema([
                        Repeater::make('size_presets')
                            ->label('Daftar Ukuran Standar')
                            ->schema([
                                Grid::make(4)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Ukuran')
                                        ->placeholder('A4, A3, 60x90cm, 80x160cm')
                                        ->required(),

                                    Forms\Components\TextInput::make('dimensions')
                                        ->label('Ukuran Detail')
                                        ->placeholder('21 x 29.7 cm')
                                        ->maxLength(50),

                                    Forms\Components\TextInput::make('price_multiplier')
                                        ->label('Pengali Harga')
                                        ->numeric()
                                        ->default(1)
                                        ->step(0.1)
                                        ->helperText('1 = harga normal, 1.5 = +50%'),

                                    Forms\Components\Toggle::make('is_popular')
                                        ->label('Ukuran Populer')
                                        ->default(false),
                                ])
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Ukuran')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state) => $state['name'] ?? 'Ukuran baru'),
                    ])
                    ->visible(fn (Get $get) => in_array($get('pricing_type'), ['per_meter_square', 'per_meter_linear', 'fixed_size'])),

                Section::make('Tier Harga Volume')
                    ->description('Berikan harga khusus untuk pembelian dalam jumlah banyak')
                    ->schema([
                        Repeater::make('volume_pricing')
                            ->label('Harga Berdasarkan Volume')
                            ->schema([
                                Grid::make(4)->schema([
                                    Forms\Components\TextInput::make('min_qty')
                                        ->label('Min. Qty')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1),

                                    Forms\Components\TextInput::make('max_qty')
                                        ->label('Max. Qty')
                                        ->numeric()
                                        ->helperText('Kosong = unlimited'),

                                    Forms\Components\TextInput::make('discount_percent')
                                        ->label('Diskon (%)')
                                        ->numeric()
                                        ->suffix('%')
                                        ->minValue(0)
                                        ->maxValue(100)
                                        ->default(0),

                                    Forms\Components\TextInput::make('label')
                                        ->label('Label')
                                        ->placeholder('Retail, Grosir, Reseller')
                                        ->maxLength(30),
                                ]),

                                Forms\Components\Placeholder::make('preview')
                                    ->label('Preview Harga')
                                    ->content(function (Get $get) {
                                        $min = $get('min_qty') ?: 1;
                                        $max = $get('max_qty');
                                        $discount = $get('discount_percent') ?: 0;
                                        $basePrice = $get('../../base_price') ?: 0;

                                        $finalPrice = $basePrice * (1 - ($discount / 100));

                                        $range = $max ? "{$min}-{$max}" : "≥{$min}";
                                        $priceText = "Rp " . number_format($finalPrice, 0, ',', '.');

                                        return "**{$range} unit** → {$priceText} ({$discount}% off)";
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Tier Volume')
                            ->collapsible()
                            ->itemLabel(function (array $state) {
                                $min = $state['min_qty'] ?? '';
                                $max = $state['max_qty'] ?? '';
                                $discount = $state['discount_percent'] ?? 0;

                                $range = $max ? "{$min}-{$max}" : "≥{$min}";
                                return "{$range} (-{$discount}%)";
                            }),

                        // Quick templates
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('add_standard_tiers')
                                ->label('Template Standar')
                                ->action(function (Set $set) {
                                    $set('volume_pricing', [
                                        ['min_qty' => 1, 'max_qty' => 49, 'discount_percent' => 0, 'label' => 'Retail'],
                                        ['min_qty' => 50, 'max_qty' => 199, 'discount_percent' => 10, 'label' => 'Grosir'],
                                        ['min_qty' => 200, 'max_qty' => null, 'discount_percent' => 20, 'label' => 'Reseller'],
                                    ]);
                                })
                                ->color('primary'),

                            Forms\Components\Actions\Action::make('add_printing_tiers')
                                ->label('Template Printing')
                                ->action(function (Set $set) {
                                    $set('volume_pricing', [
                                        ['min_qty' => 1, 'max_qty' => 10, 'discount_percent' => 0, 'label' => 'Retail'],
                                        ['min_qty' => 11, 'max_qty' => 50, 'discount_percent' => 15, 'label' => 'Bulk'],
                                        ['min_qty' => 51, 'max_qty' => null, 'discount_percent' => 25, 'label' => 'Wholesale'],
                                    ]);
                                })
                                ->color('info'),
                        ]),
                    ]),

                Section::make('Material & Finishing')
                    ->description('Spesifikasi bahan dan finishing yang tersedia')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('default_material')
                                ->label('Bahan Default')
                                ->options([
                                    'vinyl_outdoor' => 'Vinyl Outdoor',
                                    'vinyl_indoor' => 'Vinyl Indoor',
                                    'canvas' => 'Canvas',
                                    'flexi_korea' => 'Flexi Korea',
                                    'albatros' => 'Albatros',
                                    'sticker_vinyl' => 'Sticker Vinyl',
                                    'sticker_transparent' => 'Sticker Transparan',
                                    'one_way_vision' => 'One Way Vision',
                                ])
                                ->searchable(),

                            Forms\Components\Select::make('default_finishing')
                                ->label('Finishing Default')
                                ->options([
                                    'cutting' => 'Cutting Biasa',
                                    'cutting_weld' => 'Cutting + Weld',
                                    'eyelet' => 'Eyelet/Ring',
                                    'laminating' => 'Laminating',
                                    'mounting' => 'Mounting Foam Board',
                                ])
                                ->searchable(),
                        ]),

                        Repeater::make('material_options')
                            ->label('Pilihan Bahan')
                            ->schema([
                                Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Bahan')
                                        ->required()
                                        ->placeholder('Vinyl Premium, Canvas Waterproof'),

                                    Forms\Components\TextInput::make('price_adjustment')
                                        ->label('Penyesuaian Harga (Rp)')
                                        ->numeric()
                                        ->default(0)
                                        ->prefix('Rp')
                                        ->helperText('+5000 untuk upgrade, -2000 untuk downgrade'),

                                    Forms\Components\Toggle::make('is_premium')
                                        ->label('Bahan Premium')
                                        ->default(false),
                                ])
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Opsi Bahan')
                            ->collapsible(),

                        Repeater::make('finishing_options')
                            ->label('Pilihan Finishing')
                            ->schema([
                                Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Finishing')
                                        ->required()
                                        ->placeholder('Cutting + Eyelet, Laminating Doff'),

                                    Forms\Components\TextInput::make('price_adjustment')
                                        ->label('Tambahan Harga (Rp)')
                                        ->numeric()
                                        ->default(0)
                                        ->prefix('Rp'),

                                    Forms\Components\Toggle::make('is_recommended')
                                        ->label('Direkomendasikan')
                                        ->default(false),
                                ])
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('+ Tambah Opsi Finishing')
                            ->collapsible(),
                    ])
                    ->collapsed(),

                Section::make('Upload File & Desain')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Toggle::make('requires_design_file')
                                ->label('Wajib Upload File Desain')
                                ->default(true)
                                ->helperText('Customer harus upload file desain'),

                            Forms\Components\Toggle::make('offers_design_service')
                                ->label('Menyediakan Jasa Desain')
                                ->default(false)
                                ->helperText('Bisa buatkan desain untuk customer'),
                        ]),

                        Forms\Components\Textarea::make('file_requirements')
                            ->label('Ketentuan File Upload')
                            ->placeholder('File dalam format AI, CDR, PDF, atau PNG/JPG minimal 300 DPI. Ukuran maksimal 10MB.')
                            ->rows(3),

                        Grid::make(3)->schema([
                            Forms\Components\TextInput::make('max_file_size_mb')
                                ->label('Max. File Size (MB)')
                                ->numeric()
                                ->default(10)
                                ->minValue(1)
                                ->maxValue(100),

                            Forms\Components\TagsInput::make('allowed_formats')
                                ->label('Format File Diterima')
                                ->default(['pdf', 'ai', 'cdr', 'psd', 'eps', 'jpg', 'png'])
                                ->suggestions(['pdf', 'ai', 'cdr', 'psd', 'eps', 'svg', 'jpg', 'png']),

                            Forms\Components\TextInput::make('design_service_price')
                                ->label('Tarif Jasa Desain (Rp)')
                                ->numeric()
                                ->prefix('Rp')
                                ->visible(fn (Get $get) => $get('offers_design_service')),
                        ]),
                    ]),

                Section::make('Produksi & Pengiriman')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('production_days')
                                    ->label('Waktu Produksi (hari)')
                                    ->numeric()
                                    ->default(2)
                                    ->minValue(0)
                                    ->maxValue(30)
                                    ->suffix('hari kerja'),

                                Forms\Components\Select::make('production_priority')
                                    ->label('Prioritas Produksi')
                                    ->options([
                                        'standard' => 'Standard (2-3 hari)',
                                        'express' => 'Express (1 hari)',
                                        'same_day' => 'Same Day (+50% harga)',
                                    ])
                                    ->default('standard'),

                                Forms\Components\TextInput::make('estimated_weight_per_unit')
                                    ->label('Est. Berat per Unit (gram)')
                                    ->numeric()
                                    ->suffix('gr')
                                    ->helperText('Untuk estimasi ongkir'),
                            ]),

                        Forms\Components\Toggle::make('requires_approval')
                            ->label('Perlu Approval Desain')
                            ->default(true)
                            ->helperText('Kirim proof sebelum produksi'),

                        Forms\Components\Textarea::make('production_notes')
                            ->label('Catatan Produksi')
                            ->placeholder('Catatan khusus untuk tim produksi...')
                            ->rows(2),
                    ])
                    ->collapsed(),

                Section::make('SEO & Marketing')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(60)
                                ->helperText('Optimal: 50-60 karakter'),

                            Forms\Components\TagsInput::make('keywords')
                                ->label('Keywords')
                                ->placeholder('banner murah, printing bandung')
                                ->helperText('Keywords untuk SEO'),
                        ]),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Deskripsi untuk Google Search (150-160 karakter)'),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->circular()
                    ->size(50),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    }),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('product_type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'banner' => 'success',
                        'sticker' => 'warning',
                        'backdrop' => 'info',
                        'roll_banner' => 'primary',
                        'signage' => 'danger',
                        'merchandise' => 'gray',
                        'packaging' => 'yellow',
                        'display' => 'purple',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'banner' => 'Banner',
                        'sticker' => 'Stiker',
                        'backdrop' => 'Backdrop',
                        'roll_banner' => 'Roll Banner',
                        'signage' => 'Signage',
                        'merchandise' => 'Merchandise',
                        'packaging' => 'Packaging',
                        'display' => 'Display',
                        default => 'Lainnya',
                    }),

                TextColumn::make('pricing_type')
                    ->label('Sistem Harga')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'per_piece' => 'success',
                        'per_meter_square' => 'info',
                        'per_meter_linear' => 'warning',
                        'fixed_size' => 'primary',
                        'bulk_package' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'per_piece' => 'Per Pcs',
                        'per_meter_square' => 'Per m²',
                        'per_meter_linear' => 'Per Meter',
                        'fixed_size' => 'Ukuran Tetap',
                        'bulk_package' => 'Paket Bulk',
                        default => 'Standar',
                    }),

                TextColumn::make('base_price')
                    ->label('Harga Dasar')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('production_days')
                    ->label('Produksi')
                    ->suffix(' hari')
                    ->alignCenter()
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state <= 1 => 'success',
                        $state <= 3 => 'warning',
                        default => 'danger',
                    }),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                ToggleColumn::make('is_featured')
                    ->label('Unggulan'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Kategori')
                    ->multiple(),

                Tables\Filters\SelectFilter::make('product_type')
                    ->label('Jenis Produk')
                    ->options([
                        'banner' => 'Banner & Spanduk',
                        'sticker' => 'Stiker & Decal',
                        'backdrop' => 'Backdrop',
                        'roll_banner' => 'Roll Banner',
                        'signage' => 'Signage',
                        'merchandise' => 'Merchandise',
                        'packaging' => 'Packaging',
                        'display' => 'Display',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('pricing_type')
                    ->label('Sistem Harga')
                    ->options([
                        'per_piece' => 'Per Pcs',
                        'per_meter_square' => 'Per m²',
                        'per_meter_linear' => 'Per Meter',
                        'fixed_size' => 'Ukuran Tetap',
                        'bulk_package' => 'Paket Bulk',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua produk')
                    ->trueLabel('Hanya yang aktif')
                    ->falseLabel('Hanya yang non-aktif'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Produk Unggulan')
                    ->placeholder('Semua produk')
                    ->trueLabel('Hanya unggulan')
                    ->falseLabel('Bukan unggulan'),

                Tables\Filters\Filter::make('has_promo')
                    ->label('Sedang Promo')
                    ->query(fn ($query) => $query->whereNotNull('promo_price'))
                    ->toggle(),


                Tables\Filters\SelectFilter::make('production_days')
                    ->label('Waktu Produksi')
                    ->options([
                        '0-1' => 'Same Day - 1 Hari',
                        '2-3' => '2-3 Hari',
                        '4-7' => '4-7 Hari',
                        '8+' => 'Lebih dari 1 Minggu',
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['value'] === '0-1', fn ($q) => $q->whereBetween('production_days', [0, 1]))
                            ->when($data['value'] === '2-3', fn ($q) => $q->whereBetween('production_days', [2, 3]))
                            ->when($data['value'] === '4-7', fn ($q) => $q->whereBetween('production_days', [4, 7]))
                            ->when($data['value'] === '8+', fn ($q) => $q->where('production_days', '>', 7));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\Action::make('duplicate')
                        ->label('Duplikat')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('success')
                        ->action(function ($record) {
                            $newRecord = $record->replicate();
                            $newRecord->name = $record->name . ' (Copy)';
                            $newRecord->slug = $record->slug . '-copy-' . time();
                            $newRecord->sku = null; // Will be auto-generated
                            $newRecord->save();
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Non-aktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('make_featured')
                        ->label('Jadikan Unggulan')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(fn ($records) => $records->each->update(['is_featured' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('remove_featured')
                        ->label('Hapus dari Unggulan')
                        ->icon('heroicon-o-minus-circle')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['is_featured' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('update_category')
                        ->label('Update Kategori')
                        ->icon('heroicon-o-tag')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('category_id')
                                ->label('Kategori Baru')
                                ->relationship('category', 'name')
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            $records->each->update(['category_id' => $data['category_id']]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            // Bisa tambahkan relations seperti:
            // ReviewsRelationManager::class,
            // OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('is_active', true)->count();
        return $count > 50 ? 'success' : ($count > 20 ? 'warning' : 'danger');
    }

    // Helper methods untuk digunakan di tempat lain
    public static function getProductTypes(): array
    {
        return [
            'banner' => 'Banner & Spanduk',
            'sticker' => 'Stiker & Decal',
            'backdrop' => 'Backdrop & Photobooth',
            'roll_banner' => 'Roll Banner (X-Banner, Y-Banner)',
            'signage' => 'Papan Nama & Signage',
            'merchandise' => 'Merchandise Custom',
            'packaging' => 'Kemasan Custom',
            'display' => 'Display & Promosi',
        ];
    }

    public static function getPricingTypes(): array
    {
        return [
            'per_piece' => 'Per Piece/Satuan',
            'per_meter_square' => 'Per Meter Persegi',
            'per_meter_linear' => 'Per Meter Linear',
            'fixed_size' => 'Ukuran Tetap',
            'bulk_package' => 'Paket Bulk/Grosir',
        ];
    }
}
