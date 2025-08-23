<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentsResource\Pages;
use App\Models\Payments;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsResource extends Resource
{
    protected static ?string $model = Payments::class;

    protected static ?string $navigationGroup = 'Transactions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Order')
                    ->relationship('order', 'order_number') // relasi order() di model Payments
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $order = \App\Models\Orders::find($state);
                            if ($order) {
                                $set('amount', $order->total_amount); // otomatis ambil total amount
                            }
                        } else {
                            $set('amount', null);
                        }
                    }),

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->disabled() // supaya tidak bisa diubah manual
                    ->dehydrated(true) // tetap simpan ke DB
                    ->required(),

                Forms\Components\FileUpload::make('payment_proof')
                    ->label('Payment Proof')
                    ->directory('payment-proofs') // folder di storage/app/public
                    ->visibility('public') // biar bisa diakses
                    ->image() // kalau file-nya gambar
                    ->maxSize(2048) // max 2MB
                    ->required(),

                Forms\Components\Select::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'approved',
                        'rejected' => 'rejected',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\TextInput::make('verified_by')
                    ->numeric()
                    ->nullable(),

                Forms\Components\Textarea::make('verification_notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Proof')
                    ->square(),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'primary',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'warning' => 'pending',
                    ]),

                Tables\Columns\TextColumn::make('verified_by')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayments::route('/create'),
            'edit' => Pages\EditPayments::route('/{record}/edit'),
        ];
    }
}
