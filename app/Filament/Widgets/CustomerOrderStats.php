<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerOrderStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        
        // Get customer dari user
        $customer = $user->customer;
        
        if (!$customer) {
            return [
                Stat::make('Total Pesanan', '0')
                    ->description('Belum ada pesanan')
                    ->descriptionIcon('heroicon-m-shopping-bag')
                    ->color('gray'),
            ];
        }

        $totalOrders = Order::where('customer_id', $customer->id)->count();
        
        $pendingOrders = Order::where('customer_id', $customer->id)
            ->where('status', Order::STATUS_PENDING_PAYMENT)
            ->count();
            
        $processingOrders = Order::where('customer_id', $customer->id)
            ->whereIn('status', [
                Order::STATUS_PAID,
                Order::STATUS_PROCESSING,
                Order::STATUS_READY
            ])
            ->count();
            
        $shippedOrders = Order::where('customer_id', $customer->id)
            ->where('status', Order::STATUS_SHIPPED)
            ->count();
            
        $completedOrders = Order::where('customer_id', $customer->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->count();
            
        // ✅ Total Belanja = subtotal_items ONLY (tanpa ongkir)
        $totalSpent = Order::where('customer_id', $customer->id)
            ->where('payment_status', Order::PAYMENT_STATUS_PAID)
            ->sum('subtotal_items');

        return [
            Stat::make('Total Pesanan', $totalOrders)
                ->description('Semua pesanan Anda')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5]),
                
            Stat::make('Menunggu Pembayaran', $pendingOrders)
                ->description('Segera lakukan pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url($pendingOrders > 0 ? route('public.track.index') : null),
                
            Stat::make('Sedang Diproses', $processingOrders)
                ->description('Pesanan dalam produksi')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info'),
                
            Stat::make('Dalam Pengiriman', $shippedOrders)
                ->description('Pesanan sedang dikirim')
                ->descriptionIcon('heroicon-m-truck')
                ->color('success')
                ->url($shippedOrders > 0 ? route('public.track.index') : null),
                
            Stat::make('Selesai', $completedOrders)
                ->description('Pesanan telah selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Total Belanja', 'Rp ' . number_format($totalSpent, 0, ',', '.'))
                ->description('Total pembelian Anda')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
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
}
