<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kategori')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Category::class, 'slug', ignoreRecord: true),
                            ]),

                        Forms\Components\Select::make('parent_id')
                            ->label('Kategori Induk')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih jika ini adalah sub-kategori'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Kategori')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media & Tampilan')
                    ->schema([
                            FileUpload::make('image')
                            ->label('Gambar Kategori')
                            ->image()
                            ->directory('categories')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->columnSpanFull(),

                        FileUpload::make('icon')
                            ->label('Icon Kategori (SVG/PNG)')
                            ->acceptedFileTypes(['image/svg+xml', 'image/png'])
                            ->directory('categories/icons')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\ColorPicker::make('color')
                                    ->label('Warna Tema')
                                    ->hex()
                                    ->default('#3B82F6'),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Angka kecil akan tampil lebih dulu'),
                            ]),
                    ]),

                Section::make('Pengaturan Produk')
                    ->schema([
                        Forms\Components\Toggle::make('requires_design_file')
                            ->label('Wajib Upload Desain')
                            ->default(true)
                            ->helperText('Apakah produk di kategori ini wajib upload file desain?'),

                        Forms\Components\TagsInput::make('available_materials')
                            ->label('Bahan yang Tersedia')
                            ->placeholder('Flexi China, Vinyl, Sticker, dll')
                            ->suggestions([
                                'Flexi China', 'Vinyl', 'Sticker Chromo', 'Sticker Transparan',
                                'Banner Korea', 'MMT', 'Corrugated Plastic', 'Foam Board',
                                'Acrylic', 'Aluminium Composite', 'PVC Board',
                            ]),

                        Forms\Components\TagsInput::make('available_finishes')
                            ->label('Finishing yang Tersedia')
                            ->placeholder('Glossy, Matte, Doff, dll')
                            ->suggestions([
                                'Glossy', 'Matte', 'Doff', 'Laminating', 'UV Coating',
                                'Emboss', 'Spot UV', 'Hot Stamping',
                            ]),

                        Forms\Components\TagsInput::make('standard_sizes')
                            ->label('Ukuran Standar')
                            ->placeholder('60x100, A3, A4, dll')
                            ->suggestions([
                                'A5 (14.8x21)', 'A4 (21x29.7)', 'A3 (29.7x42)', 'A2 (42x59.4)', 'A1 (59.4x84.1)',
                                '60x100', '80x120', '100x150', '120x200', '150x300',
                                'Custom Size',
                            ]),
                    ]),

                Section::make('Template & Panduan')
                    ->schema([
                        Forms\Components\FileUpload::make('design_templates')
                            ->label('Template Desain')
                            ->multiple()
                            ->directory('categories/templates')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                            ->helperText('Upload template desain untuk kategori ini'),

                        Forms\Components\RichEditor::make('design_guidelines')
                            ->label('Panduan Desain')
                            ->placeholder('Tulis panduan desain untuk kategori ini...')
                            ->helperText('Panduan ukuran, resolusi, bleed area, dll'),

                        Forms\Components\Textarea::make('production_notes')
                            ->label('Catatan Produksi')
                            ->placeholder('Catatan khusus untuk produksi kategori ini...')
                            ->rows(3),
                    ]),

                Section::make('SEO & Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true),

                                Forms\Components\Toggle::make('show_on_homepage')
                                    ->label('Tampilkan di Homepage')
                                    ->default(false),
                            ]),

                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Optimal: 50-60 karakter'),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->rows(3)
                            ->helperText('Optimal: 150-160 karakter'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular()
                    ->size(40),

                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $record): string => $record->parent?->name ?? ''),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('products_count')
                    ->label('Jumlah Produk')
                    ->counts('products')
                    ->alignCenter(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignCenter(),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                ToggleColumn::make('show_on_homepage')
                    ->label('Homepage'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Aktif saja')
                    ->falseLabel('Tidak aktif saja')
                    ->native(false),

                Tables\Filters\SelectFilter::make('parent')
                    ->relationship('parent', 'name')
                    ->label('Kategori Induk'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
