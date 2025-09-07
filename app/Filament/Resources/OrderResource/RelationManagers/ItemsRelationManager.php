<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title = 'Order Items';
    protected static ?string $modelLabel = 'Item';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                Forms\Components\TextInput::make('price')
                    ->label('Unit Price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Forms\Components\TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated(false),

                Forms\Components\Section::make('Custom Specifications')
                    ->schema([
                        Forms\Components\TextInput::make('custom_size_width')
                            ->label('Width (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0),

                        Forms\Components\TextInput::make('custom_size_height')
                            ->label('Height (cm)')
                            ->numeric()
                            ->step(0.1)
                            ->minValue(0),

                        Forms\Components\TextInput::make('selected_material')
                            ->label('Material'),

                        Forms\Components\TextInput::make('selected_finishing')
                            ->label('Finishing'),

                        Forms\Components\Toggle::make('requires_design_service')
                            ->label('Requires Design Service'),

                        Forms\Components\Textarea::make('design_notes')
                            ->label('Design Notes')
                            ->rows(3),
                    ])->columns(2),

                Forms\Components\Section::make('Design File')
                    ->schema([
                        Forms\Components\FileUpload::make('design_file_path')
                            ->label('Design File')
                            ->disk('public')
                            ->directory('designs/orders')
                            ->acceptedFileTypes([
                                'image/jpeg', 'image/png', 'image/gif',
                                'application/pdf',
                                'application/postscript', // AI files
                                'image/vnd.adobe.photoshop', // PSD files
                            ])
                            ->maxSize(10240) // 10MB
                            ->downloadable()
                            ->openable(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                Tables\Columns\ImageColumn::make('product.featured_image')
                    ->label('Image')
                    ->disk('public')
                    ->size(60)
                    ->square(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->product->name ?? 'N/A';
                    }),

                Tables\Columns\TextColumn::make('product.sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('custom_size')
                    ->label('Size')
                    ->getStateUsing(function ($record) {
                        if ($record->custom_size_width && $record->custom_size_height) {
                            return $record->custom_size_width . ' x ' . $record->custom_size_height . ' cm';
                        }
                        return 'Standard';
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('selected_material')
                    ->label('Material')
                    ->limit(20)
                    ->placeholder('Standard')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('selected_finishing')
                    ->label('Finishing')
                    ->limit(20)
                    ->placeholder('None')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('requires_design_service')
                    ->label('Design Service')
                    ->boolean()
                    ->trueIcon('heroicon-o-paint-brush')
                    ->falseIcon('heroicon-o-minus')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('has_design_file')
                    ->label('Design File')
                    ->getStateUsing(fn ($record) => !empty($record->design_file_path))
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-document-minus')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Unit Price')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->money('IDR')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added At')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('download_design')
                    ->label('Download Design')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn ($record) => !empty($record->design_file_path))
                    ->url(fn ($record) => Storage::disk('public')->url($record->design_file_path), shouldOpenInNewTab: true),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
