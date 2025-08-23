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

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Catalog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Product::class, 'slug', ignoreRecord: true),
                            ]),

                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('sku')
                            ->label('SKU/Kode Produk')
                            ->maxLength(100)
                            ->unique(Product::class, 'sku', ignoreRecord: true),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ]),

                Section::make('Deskripsi & Konten')
                    ->schema([
                        Forms\Components\Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->maxLength(500),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Lengkap')
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & Gambar')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Gambar Utama')
                            ->image()
                            ->directory('products/featured')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),

                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Galeri Gambar')
                            ->image()
                            ->directory('products/gallery')
                            ->multiple()
                            ->reorderable()
                            ->imageEditor()
                            ->maxFiles(10),
                    ])
                    ->columns(1),

                // ENHANCED: Flexible Pricing Section
                Section::make('Tipe Harga & Penjualan')
                    ->description('Tentukan bagaimana produk ini dijual dan dihitung harganya')
                    ->schema([
                        Forms\Components\Select::make('pricing_type')
                            ->label('Sistem Harga')
                            ->options([
                                'per_piece' => '🏷️ Per Satuan (Stiker, Pin, Gantungan Kunci)',
                                'per_meter_square' => '📐 Per Meter Persegi (Banner, Spanduk, Backdrop)',
                                'per_meter_linear' => '📏 Per Meter Linear (Pita, Tali, Roll)',
                                'bulk_tier' => '📦 Harga Grosir/Tier (Kemasan, Paket Besar)',
                            ])
                            ->default('per_piece')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                // Auto set defaults based on pricing type
                                match ($state) {
                                    'per_piece' => [
                                        $set('unit_label', 'pcs'),
                                        $set('has_custom_size', false),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1)
                                    ],
                                    'per_meter_square' => [
                                        $set('unit_label', 'm²'),
                                        $set('has_custom_size', true),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1)
                                    ],
                                    'per_meter_linear' => [
                                        $set('unit_label', 'meter'),
                                        $set('has_custom_size', true),
                                        $set('minimum_quantity', 1),
                                        $set('step_quantity', 1)
                                    ],
                                    'bulk_tier' => [
                                        $set('unit_label', 'pcs'),
                                        $set('has_custom_size', false),
                                        $set('minimum_quantity', 50),
                                        $set('step_quantity', 50)
                                    ],
                                    default => null,
                                };
                            }),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('unit_label')
                                    ->label('Satuan Unit')
                                    ->placeholder('pcs, m², meter, set, pak')
                                    ->required()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('minimum_quantity')
                                    ->label('Minimal Pembelian')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1),

                                Forms\Components\TextInput::make('step_quantity')
                                    ->label('Kelipatan Order')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->helperText('Misal: 50 untuk grosir, 1 untuk retail')
                                    ->minValue(1),
                            ]),
                    ]),

                Section::make('Harga & Stok')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label(function (Get $get) {
                                        $type = $get('pricing_type');
                                        return match ($type) {
                                            'per_meter_square' => 'Harga per m² (Rp)',
                                            'per_meter_linear' => 'Harga per meter (Rp)',
                                            'bulk_tier' => 'Harga grosir (Rp)',
                                            default => 'Harga per ' . ($get('unit_label') ?: 'pcs') . ' (Rp)',
                                        };
                                    })
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('sale_price')
                                    ->label('Harga Promo (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Kosongkan jika tidak ada promo'),

                                Forms\Components\TextInput::make('stock_quantity')
                                    ->label(function (Get $get) {
                                        $unit = $get('unit_label') ?: 'pcs';
                                        return "Stok ($unit)";
                                    })
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('minimum_order')
                                    ->label('Minimum Order (DEPRECATED)')
                                    ->numeric()
                                    ->default(1)
                                    ->helperText('⚠️ Field ini akan diganti dengan minimum_quantity'),

                                Forms\Components\Select::make('stock_status')
                                    ->label('Status Stok')
                                    ->options([
                                        'in_stock' => 'Tersedia',
                                        'out_of_stock' => 'Habis',
                                        'on_backorder' => 'Pre-order',
                                    ])
                                    ->default('in_stock'),
                            ]),
                    ]),

                // ENHANCED: Flexible Size Options
                Section::make('Pengaturan Ukuran')
                    ->description('Konfigurasikan opsi ukuran untuk produk')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Toggle::make('has_custom_size')
                                ->label('Customer Bisa Input Ukuran Custom')
                                ->helperText('Untuk banner/spanduk yang bisa dipesan dengan ukuran khusus')
                                ->live(),

                            Forms\Components\Toggle::make('has_size_presets')
                                ->label('Ada Preset Ukuran Standar')
                                ->helperText('Sediakan pilihan ukuran yang sudah ditetapkan')
                                ->live(),
                        ]),

                        // Size Presets (jika diaktifkan)
                        Repeater::make('size_presets')
                            ->label('Ukuran Standar')
                            ->schema([
                                Grid::make(4)->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Ukuran')
                                        ->placeholder('A4, A3, 60x90cm')
                                        ->required(),

                                    Forms\Components\TextInput::make('description')
                                        ->label('Keterangan')
                                        ->placeholder('21 x 29.7 cm')
                                        ->maxLength(100),

                                    Forms\Components\TextInput::make('price_adjustment')
                                        ->label('Penyesuaian Harga')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->default(0)
                                        ->helperText('+ untuk tambahan, - untuk diskon'),

                                    Forms\Components\Toggle::make('is_available')
                                        ->label('Tersedia')
                                        ->default(true),
                                ])
                            ])
                            ->visible(fn (Get $get) => $get('has_size_presets'))
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Ukuran Preset')
                            ->reorderable()
                            ->collapsible(),
                    ]),

                // ENHANCED: Quantity Tiers (untuk per_piece & bulk_tier)
                Section::make('Tier Harga Berdasarkan Jumlah')
                    ->description('Berikan diskon otomatis untuk pembelian dalam jumlah banyak')
                    ->schema([
                        Repeater::make('quantity_tiers')
                            ->label('Tier Harga Quantity')
                            ->schema([
                                Grid::make(4)->schema([
                                    Forms\Components\TextInput::make('min_quantity')
                                        ->label('Minimal Qty')
                                        ->numeric()
                                        ->required()
                                        ->minValue(1),

                                    Forms\Components\TextInput::make('price')
                                        ->label('Harga per Unit')
                                        ->numeric()
                                        ->prefix('Rp')
                                        ->required(),

                                    Forms\Components\TextInput::make('label')
                                        ->label('Label Tier')
                                        ->placeholder('Retail, Grosir, Distributor')
                                        ->maxLength(50),

                                    Forms\Components\TextInput::make('discount_percent')
                                        ->label('Diskon %')
                                        ->numeric()
                                        ->suffix('%')
                                        ->helperText('Opsional, untuk display')
                                        ->minValue(0)
                                        ->maxValue(100),
                                ])
                            ])
                            ->visible(fn (Get $get) => in_array($get('pricing_type'), ['per_piece', 'bulk_tier']))
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Tier Harga')
                            ->reorderable()
                            ->collapsible()
                            ->helperText('Contoh: 1-49 pcs = Rp 5.000, 50-99 pcs = Rp 4.500, 100+ pcs = Rp 4.000'),
                    ]),

                Section::make('Variasi Ukuran/Harga (Legacy)')
                    ->description('⚠️ Section ini akan diganti dengan sistem baru di atas')
                    ->collapsed()
                    ->schema([
                        Repeater::make('size_variants')
                            ->label('Variasi Ukuran (Legacy)')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('size')
                                            ->label('Ukuran/Range')
                                            ->required()
                                            ->placeholder('0-10, 11-50, 60x100 cm')
                                            ->helperText('Untuk area: 0-10, 11-50. Untuk ukuran: 60x100'),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required(),

                                        Forms\Components\Toggle::make('is_available')
                                            ->label('Tersedia')
                                            ->default(true),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Ukuran')
                            ->helperText('Akan dipindahkan ke sistem baru secara otomatis'),
                    ]),

                Section::make('Spesifikasi Produk')
                    ->schema([
                        Repeater::make('specifications')
                            ->label('Spesifikasi')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Spesifikasi')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Nilai')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Spesifikasi'),
                    ]),

                Section::make('Pengaturan Upload File')
                    ->schema([
                        Forms\Components\Textarea::make('file_upload_notes')
                            ->label('Catatan Upload File')
                            ->placeholder('Instruksi untuk customer tentang format file, resolusi, dll')
                            ->rows(3),

                        Forms\Components\TextInput::make('max_file_size')
                            ->label('Ukuran File Maksimal (MB)')
                            ->numeric()
                            ->default(10),

                        Forms\Components\TagsInput::make('allowed_file_types')
                            ->label('Format File yang Diterima')
                            ->placeholder('pdf, ai, cdr, psd, jpg, png')
                            ->suggestions(['pdf', 'ai', 'cdr', 'psd', 'jpg', 'png', 'eps', 'svg']),
                    ]),

                Section::make('Pengiriman & Produksi')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('weight')
                                    ->label('Berat (gram)')
                                    ->numeric()
                                    ->suffix('gr'),

                                Forms\Components\TextInput::make('production_time')
                                    ->label('Waktu Produksi')
                                    ->placeholder('2-3 hari kerja')
                                    ->maxLength(50)
                                    ->helperText('Isi dalam format teks, misal: "2-3 hari kerja"'),

                                Forms\Components\Toggle::make('requires_design_approval')
                                    ->label('Perlu Persetujuan Desain')
                                    ->default(false),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('package_length')
                                    ->label('Panjang Kemasan (cm)')
                                    ->numeric(),

                                Forms\Components\TextInput::make('package_width')
                                    ->label('Lebar Kemasan (cm)')
                                    ->numeric(),

                                Forms\Components\TextInput::make('package_height')
                                    ->label('Tinggi Kemasan (cm)')
                                    ->numeric(),
                            ]),
                    ]),

                Section::make('SEO & Marketing')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Optimal: 50-60 karakter'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Optimal: 150-160 karakter'),

                        Forms\Components\TagsInput::make('tags')
                            ->label('Tags/Keywords')
                            ->placeholder('Tambahkan tag produk'),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Produk Unggulan')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->url(fn ($record) => $record->featured_image),

                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),

                // ENHANCED: Show pricing type
                TextColumn::make('pricing_type')
                    ->label('Tipe Harga')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'per_piece' => 'success',
                        'per_meter_square' => 'info',
                        'per_meter_linear' => 'warning',
                        'bulk_tier' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'per_piece' => 'Per Pcs',
                        'per_meter_square' => 'Per m²',
                        'per_meter_linear' => 'Per Meter',
                        'bulk_tier' => 'Grosir',
                        default => 'Per Pcs',
                    })
                    ->toggleable(),

                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                // ENHANCED: Show stock with unit
                TextColumn::make('stock_quantity')
                    ->label('Stok')
                    ->sortable()
                    ->alignCenter()
                    ->formatStateUsing(fn ($state, $record) => $state . ' ' . ($record->unit_label ?? 'pcs'))
                    ->color(fn (int $state): string => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Kategori'),

                // ENHANCED: Filter by pricing type
                Tables\Filters\SelectFilter::make('pricing_type')
                    ->label('Tipe Harga')
                    ->options([
                        'per_piece' => 'Per Pcs',
                        'per_meter_square' => 'Per m²',
                        'per_meter_linear' => 'Per Meter',
                        'bulk_tier' => 'Grosir',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Aktif saja')
                    ->falseLabel('Tidak aktif saja')
                    ->native(false),

                Tables\Filters\SelectFilter::make('stock_status')
                    ->label('Status Stok')
                    ->options([
                        'in_stock' => 'Tersedia',
                        'out_of_stock' => 'Habis',
                        'on_backorder' => 'Pre-order',
                    ]),

                Tables\Filters\TernaryFilter::make('has_custom_size')
                    ->label('Custom Size')
                    ->boolean()
                    ->trueLabel('Bisa custom size')
                    ->falseLabel('Ukuran tetap saja'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // ENHANCED: Bulk actions
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                        }),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Non-aktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
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
        return static::getModel()::count();
    }
}
