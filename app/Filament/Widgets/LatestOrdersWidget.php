<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = '📦 Pesanan Masuk Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['customer', 'items'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-shopping-cart')
                    ->iconColor('primary')
                    ->copyable()
                    ->copyMessage('Order ID disalin!')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.view', $record->id)),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->limit(25)
                    ->tooltip(fn (Order $record): string => $record->customer->email ?? ''),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING_PAYMENT => 'danger',
                        Order::STATUS_PAID => 'warning',
                        Order::STATUS_PROCESSING => 'info',
                        Order::STATUS_READY => 'primary',
                        Order::STATUS_SHIPPED => 'gray',
                        Order::STATUS_COMPLETED => 'success',
                        Order::STATUS_CANCELLED => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING_PAYMENT => 'heroicon-m-clock',
                        Order::STATUS_PAID => 'heroicon-m-check-circle',
                        Order::STATUS_PROCESSING => 'heroicon-m-cog-6-tooth',
                        Order::STATUS_READY => 'heroicon-m-cube',
                        Order::STATUS_SHIPPED => 'heroicon-m-truck',
                        Order::STATUS_COMPLETED => 'heroicon-m-check-badge',
                        Order::STATUS_CANCELLED => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING_PAYMENT => 'Pending',
                        Order::STATUS_PAID => 'Paid',
                        Order::STATUS_PROCESSING => 'Processing',
                        Order::STATUS_READY => 'Ready',
                        Order::STATUS_SHIPPED => 'Shipped',
                        Order::STATUS_COMPLETED => 'Completed',
                        Order::STATUS_CANCELLED => 'Cancelled',
                        default => $state,
                    }),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M, H:i')
                    ->sortable()
                    ->description(fn (Order $record): string => $record->created_at->diffForHumans())
                    ->icon('heroicon-m-clock')
                    ->color('gray'),
            ])
            ->actions([
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->url(fn (Order $record): string => \App\Filament\Resources\OrderResource::generateWhatsAppUrl($record))
                    ->openUrlInNewTab(),
                    
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-m-eye'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Tidak ada pesanan')
            ->emptyStateDescription('Belum ada pesanan yang masuk.')
            ->emptyStateIcon('heroicon-m-shopping-cart');
    }
}
