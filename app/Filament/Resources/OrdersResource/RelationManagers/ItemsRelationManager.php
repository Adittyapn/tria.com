<?php

namespace App\Filament\Resources\OrdersResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items'; // harus sama persis sama nama relasi di model Orders

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $product = \App\Models\Product::find($state);
                            if ($product) {
                                $set('price', $product->price);
                                $set('subtotal', $get('quantity') * $product->price);
                            }
                        } else {
                            $set('price', null);
                            $set('subtotal', null);
                        }
                    }),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $set('subtotal', $state * $get('price'));
                    }),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),

                Forms\Components\TextInput::make('subtotal')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(true) // biar tetep ke-save walau disabled
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name') // tampilkan nama produk sebagai title
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity'),

                Tables\Columns\TextColumn::make('price')
                    ->money('idr'),

                Tables\Columns\TextColumn::make('subtotal')
                    ->money('idr'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
