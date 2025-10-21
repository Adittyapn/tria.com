<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $modelLabel = 'Order';
    protected static ?string $pluralModelLabel = 'Orders';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Transactions';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->disabled()
                            ->default(fn () => Order::generateOrderNumber()),

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
                                Order::STATUS_PAID => 'Paid',
                                Order::STATUS_PROCESSING => 'Processing',
                                Order::STATUS_READY => 'Ready to Ship',
                                Order::STATUS_SHIPPED => 'Shipped',
                                Order::STATUS_COMPLETED => 'Completed',
                                Order::STATUS_CANCELLED => 'Cancelled',
                            ])
                            ->required()
                            ->default(Order::STATUS_PENDING_PAYMENT),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                Order::PAYMENT_STATUS_PENDING => 'Pending',
                                Order::PAYMENT_STATUS_PAID => 'Paid',
                                Order::PAYMENT_STATUS_FAILED => 'Failed',
                            ])
                            ->required()
                            ->default(Order::PAYMENT_STATUS_PENDING),
                    ])->columns(2),

                Forms\Components\Section::make('Shipping Information')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_province_name')
                            ->label('Province')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_city_name')
                            ->label('City')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_district_name')
                            ->label('District')
                            ->disabled(),

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Address')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('shipping_courier')
                            ->label('Courier')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_service')
                            ->label('Service')
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label('Shipping Cost')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('shipping_etd')
                            ->label('Estimated Delivery')
                            ->disabled(),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->placeholder('Input tracking number when shipped'),
                    ])->columns(3),

                Forms\Components\Section::make('Order Summary')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal_items')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Tax (11%)')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->prefix('Rp')
                            ->numeric()
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Notes & Files')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Order Notes')
                            ->rows(3),

                        Forms\Components\FileUpload::make('payment_proof')
                            ->label('Payment Proof')
                            ->disk('public')
                            ->directory('payment-proofs')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                            ->maxSize(5120) // 5MB
                            ->downloadable()
                            ->disabled(fn ($record) => $record && $record->payment_proof),
                    ])->columns(1),

                Forms\Components\Section::make('Timestamps')
                    ->schema([
                        Forms\Components\DateTimePicker::make('created_at')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('updated_at')
                            ->disabled(),
                    ])->columns(2)
                    ->visibleOn('edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => Order::STATUS_PENDING_PAYMENT,
                        'success' => Order::STATUS_PAID,
                        'info' => Order::STATUS_PROCESSING,
                        'primary' => Order::STATUS_READY,
                        'secondary' => Order::STATUS_SHIPPED,
                        'success' => Order::STATUS_COMPLETED,
                        'danger' => Order::STATUS_CANCELLED,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
                        Order::STATUS_PAID => 'Paid',
                        Order::STATUS_PROCESSING => 'Processing',
                        Order::STATUS_READY => 'Ready to Ship',
                        Order::STATUS_SHIPPED => 'Shipped',
                        Order::STATUS_COMPLETED => 'Completed',
                        Order::STATUS_CANCELLED => 'Cancelled',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'warning' => Order::PAYMENT_STATUS_PENDING,
                        'success' => Order::PAYMENT_STATUS_PAID,
                        'danger' => Order::PAYMENT_STATUS_FAILED,
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Order::PAYMENT_STATUS_PENDING => 'Pending',
                        Order::PAYMENT_STATUS_PAID => 'Paid',
                        Order::PAYMENT_STATUS_FAILED => 'Failed',

                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('shipping_courier')
                    ->label('Courier')
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('shipping_city_name')
                    ->label('Destination')
                    ->limit(20)
                    ->tooltip(function (Order $record): string {
                        $address = [];
                        if ($record->shipping_district_name) {
                            $address[] = $record->shipping_district_name;
                        }
                        $address[] = $record->shipping_city_name;
                        $address[] = $record->shipping_province_name;
                        return implode(', ', $address);
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking')
                    ->searchable()
                    ->copyable()
                    ->placeholder('No tracking')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('payment_proof')
                    ->label('Payment Proof')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-document-minus')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        Order::STATUS_PENDING_PAYMENT => 'Pending Payment',
                        Order::STATUS_PAID => 'Paid',
                        Order::STATUS_PROCESSING => 'Processing',
                        Order::STATUS_READY => 'Ready to Ship',
                        Order::STATUS_SHIPPED => 'Shipped',
                        Order::STATUS_COMPLETED => 'Completed',
                        Order::STATUS_CANCELLED => 'Cancelled',
                    ]),

                SelectFilter::make('payment_status')
                    ->options([
                        Order::PAYMENT_STATUS_PENDING => 'Pending',
                        Order::PAYMENT_STATUS_PAID => 'Paid',
                        Order::PAYMENT_STATUS_FAILED => 'Failed',

                    ]),

                SelectFilter::make('shipping_courier')
                    ->options([
                        'jne' => 'JNE',
                        'pos' => 'POS Indonesia',
                        'tiki' => 'TIKI',
                        'sicepat' => 'SiCepat',
                        'jnt' => 'J&T',
                        'ninja' => 'Ninja Express',
                        'lion' => 'Lion Parcel',
                        'anteraja' => 'AnterAja',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // WhatsApp Contact Action
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Order $record): string => static::generateWhatsAppUrl($record))
                    ->openUrlInNewTab(),

                // Quick status update actions
                Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool =>
                        $record->payment_status === Order::PAYMENT_STATUS_PENDING)
                    ->action(function (Order $record): void {
                        $record->update([
                            'payment_status' => Order::PAYMENT_STATUS_PAID,
                            'status' => Order::STATUS_PAID,
                        ]);

                        Notification::make()
                            ->title('Order marked as paid')
                            ->success()
                            ->send();
                    }),

                Action::make('start_processing')
                    ->label('Start Processing')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('info')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool =>
                        $record->status === Order::STATUS_PAID)
                    ->action(function (Order $record): void {
                        $record->update(['status' => Order::STATUS_PROCESSING]);

                        Notification::make()
                            ->title('Order moved to processing')
                            ->success()
                            ->send();
                    }),

                Action::make('mark_ready')
                    ->label('Mark Ready')
                    ->icon('heroicon-o-cube')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool =>
                        $record->status === Order::STATUS_PROCESSING)
                    ->action(function (Order $record): void {
                        $record->update(['status' => Order::STATUS_READY]);

                        Notification::make()
                            ->title('Order marked as ready to ship')
                            ->success()
                            ->send();
                    }),

                Action::make('ship_order')
                    ->label('Ship Order')
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->required()
                            ->placeholder('Enter tracking number'),
                    ])
                    ->visible(fn (Order $record): bool =>
                        $record->status === Order::STATUS_READY)
                    ->action(function (Order $record, array $data): void {
                        $record->update([
                            'status' => Order::STATUS_SHIPPED,
                            'tracking_number' => $data['tracking_number'],
                        ]);

                        Notification::make()
                            ->title('Order shipped successfully')
                            ->success()
                            ->send();
                    }),

                Action::make('complete_order')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record): bool =>
                        $record->status === Order::STATUS_SHIPPED)
                    ->action(function (Order $record): void {
                        $record->update(['status' => Order::STATUS_COMPLETED]);

                        Notification::make()
                            ->title('Order completed')
                            ->success()
                            ->send();
                    }),

                Action::make('download_payment_proof')
                ->label('Payment Proof')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->visible(fn (Order $record): bool => !empty($record->payment_proof))
                ->url(fn (Order $record): string => asset('storage/' . $record->payment_proof))
                
                ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('mark_paid')
                        ->label('Mark as Paid')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(function (Order $record) {
                                if ($record->payment_status === Order::PAYMENT_STATUS_PENDING) {
                                    $record->update([
                                        'payment_status' => Order::PAYMENT_STATUS_PAID,
                                        'status' => Order::STATUS_PAID,
                                    ]);
                                }
                            });

                            Notification::make()
                                ->title('Selected orders marked as paid')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Orders')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('primary')
                        ->action(function (Collection $records): void {
                            // You can implement CSV export here
                            Notification::make()
                                ->title('Export feature coming soon')
                                ->info()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')
                            ->label('Order Number')
                            ->weight(FontWeight::Bold)
                            ->copyable(),

                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                Order::STATUS_PENDING_PAYMENT => 'warning',
                                Order::STATUS_PAID => 'success',
                                Order::STATUS_PROCESSING => 'info',
                                Order::STATUS_READY => 'primary',
                                Order::STATUS_SHIPPED => 'secondary',
                                Order::STATUS_COMPLETED => 'success',
                                Order::STATUS_CANCELLED => 'danger',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                Order::PAYMENT_STATUS_PENDING => 'warning',
                                Order::PAYMENT_STATUS_PAID => 'success',
                                Order::PAYMENT_STATUS_FAILED => 'danger',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('tracking_number')
                            ->label('Tracking Number')
                            ->copyable()
                            ->placeholder('No tracking number'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime(),
                    ])->columns(3),

                Infolists\Components\Section::make('Customer Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label('Name'),

                        Infolists\Components\TextEntry::make('customer.email')
                            ->label('Email')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('customer.phone')
                            ->label('Phone')
                            ->copyable()
                            ->placeholder('No phone'),

                        Infolists\Components\TextEntry::make('customer.address')
                            ->label('Customer Address')
                            ->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('Shipping Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('shipping_address')
                            ->label('Shipping Address')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('shipping_province_name')
                            ->label('Province'),

                        Infolists\Components\TextEntry::make('shipping_city_name')
                            ->label('City'),

                        Infolists\Components\TextEntry::make('shipping_district_name')
                            ->label('District')
                            ->placeholder('No district'),

                        Infolists\Components\TextEntry::make('shipping_courier')
                            ->label('Courier')
                            ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                        Infolists\Components\TextEntry::make('shipping_service')
                            ->label('Service'),

                        Infolists\Components\TextEntry::make('shipping_cost')
                            ->label('Shipping Cost')
                            ->money('IDR'),

                        Infolists\Components\TextEntry::make('shipping_etd')
                            ->label('Estimated Delivery')
                            ->placeholder('No ETD'),
                    ])->columns(3),

                Infolists\Components\Section::make('Order Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal_items')
                            ->label('Subtotal')
                            ->money('IDR'),

                        Infolists\Components\TextEntry::make('tax_amount')
                            ->label('Tax (11%)')
                            ->money('IDR'),

                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('IDR')
                            ->weight(FontWeight::Bold),
                    ])->columns(3),

                Infolists\Components\Section::make('Payment Proof')
                    ->schema([
                        Infolists\Components\ImageEntry::make('payment_proof')
                            ->label('Payment Proof')
                            ->disk('public')
                            ->height(300)
                            ->placeholder('No payment proof uploaded'),
                    ])
                    ->visible(fn (Order $record): bool => !empty($record->payment_proof)),

                Infolists\Components\Section::make('Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Order Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Order $record): bool => !empty($record->notes)),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', Order::STATUS_PENDING_PAYMENT)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    /**
     * Generate WhatsApp URL with pre-filled message
     */
    /**
     * Generate WhatsApp URL for admin to contact customer
     * Message: Reminder untuk upload bukti pembayaran
     */
    public static function generateWhatsAppUrl(Order $order): string
    {
        // Eager load relationships
        $order->load(['customer', 'items.product']);
        $customer = $order->customer;
        
        // Message dari Admin ke Customer untuk reminder pembayaran
        // Menggunakan Unicode escape sequences untuk emoji
        $message = "Halo *{$customer->name}* \u{1F44B}\n\n"; // 👋
        $message .= "Terima kasih telah berbelanja di *Tria Digital Printing*! \u{1F389}\n\n"; // 🎉
        
        $message .= "Pesanan Anda telah kami terima dengan detail:\n\n";
        
        $message .= "\u{1F4CB} *Detail Pesanan:*\n"; // 📋
        $message .= "Order ID: *#{$order->order_number}*\n";
        $message .= "Tanggal: " . $order->created_at->format('d M Y, H:i') . " WIB\n\n";
        
        $message .= "\u{1F4E6} *Produk yang Dipesan:*\n"; // 📦
        foreach ($order->items as $index => $item) {
            $productName = $item->product ? $item->product->name : 'Produk';
            $message .= ($index + 1) . ". {$productName} ({$item->quantity}x)\n";
            if ($item->custom_size_width && $item->custom_size_height) {
                $width = $item->custom_size_width / 100;
                $height = $item->custom_size_height / 100;
                $message .= "   Ukuran: {$width}m x {$height}m\n";
            }
        }
        $message .= "\n";
        
        $message .= "\u{1F4B0} *Total Pembayaran:*\n"; // 💰
        $message .= "*Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n\n";
        
        $message .= "─────────────────────\n\n";
        
        // Conditional message based on order status
        if ($order->status === Order::STATUS_PENDING_PAYMENT || 
            $order->payment_status === 'pending') {
            $message .= "\u{26A0}\u{FE0F} *LANGKAH SELANJUTNYA:*\n\n"; // ⚠️
            $message .= "Untuk memproses pesanan Anda, mohon segera melakukan:\n\n";
            
            $message .= "\u{0031}\u{FE0F}\u{20E3} *Transfer pembayaran* ke rekening kami:\n"; // 1️⃣
            $message .= "   Bank: [NAMA BANK]\n";
            $message .= "   No. Rek: [NOMOR REKENING]\n";
            $message .= "   Atas Nama: [NAMA PEMILIK]\n\n";
            
            $message .= "\u{0032}\u{FE0F}\u{20E3} *Upload bukti transfer* melalui:\n"; // 2️⃣
            $message .= "   \u{1F517} " . ($order->hasSecureTracking() // 🔗
                ? $order->getSecureTrackingUrl() 
                : route('orders.show', $order->order_number)) . "\n\n";
            
            $message .= "\u{0033}\u{FE0F}\u{20E3} *Setelah pembayaran kami verifikasi*, pesanan akan segera kami proses \u{26A1}\n\n"; // 3️⃣ ⚡
            
            $message .= "─────────────────────\n\n";
            
            $message .= "\u{1F4A1} *Tips:*\n"; // 💡
            $message .= "• Upload bukti transfer paling lambat 1x24 jam\n";
            $message .= "• Pastikan nominal transfer sesuai dengan total di atas\n";
            $message .= "• Simpan link tracking untuk cek status pesanan\n\n";
        } else {
            $message .= "\u{1F4CA} *Status Pesanan:*\n"; // 📊
            $message .= "Order: {$order->status_label}\n";
            $message .= "Pembayaran: {$order->payment_status_label}\n\n";
            
            $message .= "\u{1F4CD} *Pengiriman:*\n"; // 📍
            $message .= "{$order->full_shipping_address}\n";
            $message .= "Kurir: {$order->shipping_service_display}\n\n";
            
            if ($order->notes) {
                $message .= "\u{1F4DD} *Catatan:*\n{$order->notes}\n\n"; // 📝
            }
        }
        
        $message .= "Ada pertanyaan? Silakan balas pesan ini! \u{1F60A}\n\n"; // 😊
        
        $message .= "Terima kasih,\n";
        $message .= "*Tria Digital Printing Team* \u{00AE}\u{FE0F}"; // ®️
        
        // Get customer phone
        $customerPhone = $customer->phone ? preg_replace('/[^0-9]/', '', $customer->phone) : '';
        
        // Format to international
        if ($customerPhone && !str_starts_with($customerPhone, '62')) {
            if (str_starts_with($customerPhone, '0')) {
                $customerPhone = '62' . substr($customerPhone, 1);
            } else {
                $customerPhone = '62' . $customerPhone;
            }
        }
        
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$customerPhone}?text={$encodedMessage}";
    }
}
