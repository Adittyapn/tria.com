<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::with(['customer'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->default('Guest'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pesanan')
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
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Order::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
                        Order::STATUS_PAID => 'Sudah Dibayar',
                        Order::STATUS_PROCESSING => 'Produksi',
                        Order::STATUS_READY => 'Siap Kirim',
                        Order::STATUS_SHIPPED => 'Dikirim',
                        Order::STATUS_COMPLETED => 'Selesai',
                        Order::STATUS_CANCELLED => 'Dibatalkan',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Order::PAYMENT_STATUS_PENDING => 'warning',
                        Order::PAYMENT_STATUS_PAID => 'success',
                        Order::PAYMENT_STATUS_FAILED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Order::PAYMENT_STATUS_PENDING => 'Menunggu',
                        Order::PAYMENT_STATUS_PAID => 'Terverifikasi',
                        Order::PAYMENT_STATUS_FAILED => 'Ditolak',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('shipping_location')
                    ->label('Tujuan')
                    ->limit(30)
                    ->tooltip(function (Order $record): ?string {
                        return $record->full_shipping_address;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Order $record): string => $record->created_at->format('d M Y H:i:s')),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Order $record): string => route('orders.show', $record->order_number))
                    ->openUrlInNewTab(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated(false);
    }

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->roles->pluck('name')->contains('super_admin');
    }
}