<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ReportDownloadWidget extends Widget
{
    protected static string $view = 'filament.widgets.report-download-widget';
    protected static ?string $heading = 'Download Laporan Penjualan';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->roles->pluck('name')->contains('super_admin');
    }

    protected function getViewData(): array
    {
        return [
            'reports' => [
                [
                    'title' => 'Laporan Pesanan',
                    'description' => 'Data lengkap semua pesanan dengan detail pelanggan dan produk',
                    'icon' => 'heroicon-o-shopping-bag',
                    'type' => 'orders',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Laporan Produk',
                    'description' => 'Analisis penjualan produk dan performa kategori',
                    'icon' => 'heroicon-o-cube',
                    'type' => 'products',
                    'color' => 'success'
                ],
                [
                    'title' => 'Laporan Pelanggan',
                    'description' => 'Data pelanggan dengan riwayat pembelian dan statistik',
                    'icon' => 'heroicon-o-users',
                    'type' => 'customers',
                    'color' => 'info'
                ]
            ]
        ];
    }
}