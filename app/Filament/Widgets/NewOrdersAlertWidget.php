<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewOrdersAlertWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $pendingOrders = Order::where('status', Order::STATUS_PENDING_PAYMENT)->count();
        $paidOrders = Order::where('status', Order::STATUS_PAID)->count();
        $processingOrders = Order::where('status', Order::STATUS_PROCESSING)->count();
        
        // Get today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->sum('total_amount');

        return [
            Stat::make('🚨 Pesanan Menunggu Pembayaran', $pendingOrders)
                ->description('Perlu segera dikonfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'danger' : 'success')
                ->chart([7, 12, 8, 15, $pendingOrders])
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('💰 Sudah Dibayar - Siap Produksi', $paidOrders)
                ->description('Menunggu untuk diproses')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($paidOrders > 0 ? 'warning' : 'gray')
                ->chart([5, 8, 12, $paidOrders])
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('⚙️ Sedang Produksi', $processingOrders)
                ->description('Dalam proses pengerjaan')
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info')
                ->chart([3, 5, $processingOrders])
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('📅 Pesanan Hari Ini', $todayOrders)
                ->description('Total pesanan masuk hari ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary')
                ->chart([2, 5, 8, 12, $todayOrders]),

            Stat::make('💵 Pendapatan Hari Ini', 'Rp ' . number_format($todayRevenue, 0, ',', '.'))
                ->description('Total revenue hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([100000, 250000, 500000, $todayRevenue / 10000]),
        ];
    }
}
