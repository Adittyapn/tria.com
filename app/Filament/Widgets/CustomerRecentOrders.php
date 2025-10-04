<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class CustomerRecentOrders extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = Auth::user();
        
        // Cari customer yang terkait dengan user ini
        $customer = $user->customer;
        
        // Jika user tidak punya customer record, atau customer tidak ada, return query kosong
        if (!$customer) {
            return $table
                ->query(Order::query()->whereRaw('1 = 0')) // Query yang selalu kosong
                ->columns([
                    TextColumn::make('order_number')->label('No. Pesanan'),
                ])
                ->emptyStateHeading('Belum Ada Pesanan')
                ->emptyStateDescription('Anda belum memiliki pesanan. Mulai berbelanja sekarang!')
                ->emptyStateIcon('heroicon-o-shopping-bag')
                ->emptyStateActions([
                    Tables\Actions\Action::make('shop')
                        ->label('Mulai Belanja')
                        ->url(url('/'))
                        ->icon('heroicon-m-home')
                        ->color('primary'),
                ]);
        }

        return $table
            ->query(
                Order::query()
                    ->where('customer_id', $customer->id)
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-document-text')
                    ->weight('bold'),
                    
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                    
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(function (Order $record): string {
                        return match ($record->status) {
                            Order::STATUS_PENDING_PAYMENT => 'warning',
                            Order::STATUS_PAID => 'info',
                            Order::STATUS_PROCESSING => 'primary',
                            Order::STATUS_READY, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED => 'success',
                            Order::STATUS_CANCELLED => 'danger',
                            default => 'gray',
                        };
                    })
                    ->icon(function (Order $record): ?string {
                        return match ($record->status) {
                            Order::STATUS_PENDING_PAYMENT => 'heroicon-m-clock',
                            Order::STATUS_PAID => 'heroicon-m-check-circle',
                            Order::STATUS_PROCESSING => 'heroicon-m-cog-6-tooth',
                            Order::STATUS_READY => 'heroicon-m-archive-box',
                            Order::STATUS_SHIPPED => 'heroicon-m-truck',
                            Order::STATUS_COMPLETED => 'heroicon-m-check-badge',
                            Order::STATUS_CANCELLED => 'heroicon-m-x-circle',
                            default => null,
                        };
                    }),
                    
                TextColumn::make('payment_status_label')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(function (Order $record): string {
                        return match ($record->payment_status) {
                            Order::PAYMENT_STATUS_PENDING => 'warning',
                            Order::PAYMENT_STATUS_PAID => 'success',
                            Order::PAYMENT_STATUS_FAILED => 'danger',
                            default => 'gray',
                        };
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('track')
                    ->label('Lacak')
                    ->icon('heroicon-m-map-pin')
                    ->url(fn (Order $record): string => route('public.track.index') . '?order=' . $record->order_number)
                    ->color('success'),
            ])
            ->emptyStateHeading('Belum Ada Pesanan')
            ->emptyStateDescription('Anda belum memiliki pesanan. Mulai berbelanja sekarang!')
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateActions([
                Tables\Actions\Action::make('shop')
                    ->label('Mulai Belanja')
                    ->url(url('/'))
                    ->icon('heroicon-m-home')
                    ->color('primary'),
            ]);
    }

    public static function canView(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        
        // Periksa apakah user memiliki role customer melalui database
        return DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('roles')
                    ->whereRaw('roles.id = model_has_roles.role_id')
                    ->where('roles.name', 'customer');
            })
            ->exists();
    }
    
    protected function getTableHeading(): string
    {
        return 'Pesanan Terbaru';
    }
}
