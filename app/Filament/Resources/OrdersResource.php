<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdersResource\Pages;
use App\Models\Orders;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrdersResource extends Resource
{
    protected static ?string $model = Orders::class;
    protected static ?string $navigationGroup = 'Transactions';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Customer Information Section
                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Select User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $user = \App\Models\User::find($state);
                                    if ($user) {
                                        $set('name', $user->name);
                                        $set('email', $user->email);
                                        $set('phone', $user->phone ?? null);
                                    }
                                } else {
                                    $set('name', null);
                                    $set('email', null);
                                    $set('phone', null);
                                }
                            }),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Customer Name')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('email')
                                    ->label('Customer Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label('Delivery Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                // Order Information Section
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('order_number')
                                    ->label('Order Number')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Select::make('status')
                                    ->label('Order Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Delivered',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total Amount')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->live(),
                            ]),

                        Forms\Components\Textarea::make('notes')
                            ->label('Order Notes')
                            ->rows(2)
                            ->columnSpanFull()
                            ->placeholder('Additional notes for this order...'),
                    ]),

                // Order Items Section
                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            $product = \App\Models\Product::find($state);
                                            if ($product) {
                                                $set('price', $product->price);
                                                $quantity = $get('quantity') ?: 1;
                                                $set('subtotal', $quantity * $product->price);
                                                
                                                // Update total amount immediately
                                                self::updateTotalAmount($get, $set);
                                            }
                                        } else {
                                            $set('price', null);
                                            $set('subtotal', null);
                                            
                                            // Update total amount immediately
                                            self::updateTotalAmount($get, $set);
                                        }
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $price = $get('price');
                                        if ($price && $state) {
                                            $set('subtotal', $state * $price);
                                        }
                                        
                                        // Update total amount immediately
                                        self::updateTotalAmount($get, $set);
                                    }),

                                Forms\Components\TextInput::make('price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required(),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->required()
                                    ->live(),
                            ])
                            ->defaultItems(1)
                            ->createItemButtonLabel('Add Product')
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                // Small delay to ensure state is updated
                                self::updateTotalAmount($get, $set);
                            })
                            ->deleteAction(
                                fn(Forms\Components\Actions\Action $action) => $action
                                    ->after(function (callable $get, callable $set) {
                                        // Add delay to ensure item is deleted before calculation
                                        self::updateTotalAmount($get, $set);
                                    })
                            )
                            ->addAction(
                                fn(Forms\Components\Actions\Action $action) => $action
                                    ->after(function (callable $get, callable $set) {
                                        self::updateTotalAmount($get, $set);
                                    })
                            )
                            ->reorderable(false) // Disable reordering to avoid issues
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // Helper method to calculate and update total amount
    private static function updateTotalAmount(callable $get, callable $set): void
    {
        try {
            // Get the current form state
            $items = $get('items') ?? [];
            $total = 0;

            // Calculate total from all items
            foreach ($items as $item) {
                // Handle both array format and object format
                $subtotal = 0;
                
                if (is_array($item)) {
                    $subtotal = isset($item['subtotal']) ? (float) $item['subtotal'] : 0;
                } elseif (is_object($item)) {
                    $subtotal = isset($item->subtotal) ? (float) $item->subtotal : 0;
                }
                
                if ($subtotal > 0) {
                    $total += $subtotal;
                }
            }

            // Set the total amount
            $set('total_amount', $total);
            
        } catch (\Exception $e) {
            // Fallback - set to 0 if there's an error
            $set('total_amount', 0);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'pending',
                        'success' => 'paid',
                        'warning' => 'processing',
                        'info' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),

                Tables\Columns\TextColumn::make('name')
                    ->label('Customer Name')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [
            \App\Filament\Resources\OrdersResource\RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
        ];
    }
}