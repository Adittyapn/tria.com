<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 1;
    
    // Use fewer columns to avoid narrow stat cards on desktop
    protected function getColumns(): int
    {
        return 2; // two cards per row; Filament will handle responsive stacking
    }
    
    protected function getStats(): array
    {
        // Basic statistics with error handling
        try {
            // ✅ Total Penjualan = subtotal_items ONLY (tanpa ongkir)
            $totalRevenue = Order::where('payment_status', 'verified')->sum('subtotal_items') ?? 0;
            $totalOrders = Order::count() ?? 0;
            $totalCustomers = Customer::count() ?? 0;
            $activeProducts = Product::where('is_active', true)->count() ?? 0;
            
            // Monthly comparison
            $thisMonth = now()->startOfMonth();
            
            // ✅ Revenue bulan ini = subtotal_items ONLY (tanpa ongkir)
            $revenueThisMonth = Order::where('payment_status', 'verified')
                ->where('created_at', '>=', $thisMonth)
                ->sum('subtotal_items') ?? 0;
                
            $ordersThisMonth = Order::where('created_at', '>=', $thisMonth)->count() ?? 0;
            $newCustomersThisMonth = Customer::where('created_at', '>=', $thisMonth)->count() ?? 0;

        } catch (\Exception $e) {
            // Fallback values if queries fail
            $totalRevenue = $totalOrders = $totalCustomers = $pendingOrders = 0;
            $totalProducts = $activeProducts = $revenueThisMonth = $ordersThisMonth = $newCustomersThisMonth = 0;
        }

        return [
            Stat::make('Total Penjualan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Penjualan produk (tanpa ongkir)')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pesanan', number_format($totalOrders))
                ->description('Semua pesanan')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Total Pelanggan', number_format($totalCustomers))
                ->description('Pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Produk Aktif', number_format($activeProducts))
                ->description('Produk tersedia')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->roles->pluck('name')->contains('super_admin');
    }
}
