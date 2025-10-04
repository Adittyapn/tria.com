<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BaseDashboard 
{
    protected static ?string $navigationIcon = 'heroicon-s-square-3-stack-3d';
    
    public function getWidgets(): array
    {
        $user = Auth::user();
        $widgets = [];
        
        // Jika user adalah customer, tampilkan widget customer
        if ($user && $user->roles->pluck('name')->contains('customer')) {
            $widgets = [
                \App\Filament\Widgets\CustomerOrderStats::class,
                \App\Filament\Widgets\CustomerRecentOrders::class,
                \App\Filament\Widgets\CustomerActivityWidget::class,
            ];
        } else {
            // Untuk admin, tampilkan widget default atau admin
            $widgets = [
                \App\Filament\Widgets\AdminStatsOverview::class,
                \App\Filament\Widgets\SalesChart::class,
                \App\Filament\Widgets\ReportDownloadWidget::class,
            ];
        }
        
        return $widgets;
    }

    /**
     * Override max content width untuk dashboard
     */
    public function getMaxContentWidth(): ?string
    {
        return 'full'; // bisa diganti '7xl', '2xl', dll sesuai kebutuhan
    }
}
