<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = '💰 Grafik Penjualan Bulan Ini';
    protected static string $color = 'info';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '420px';

    protected function getData(): array
    {
        try {
            // Get start and end of current month
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            $daysInMonth = $endOfMonth->day; // Total days in current month (30 or 31)
            
            // Get month name in Indonesian
            $monthName = now()->locale('id')->translatedFormat('F Y');

            $days = collect(range(1, $daysInMonth))->map(function ($day) use ($startOfMonth) {
                $date = $startOfMonth->copy()->addDays($day - 1);
                return [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('d M'),
                    // ✅ Revenue = subtotal_items ONLY (tanpa ongkir)
                    'revenue' => Order::where('payment_status', 'verified')
                        ->whereDate('created_at', $date)
                        ->sum('subtotal_items') ?? 0,
                    'orders' => Order::whereDate('created_at', $date)->count() ?? 0,
                ];
            });

            // Get the raw revenue data for tooltip
            $revenueData = $days->pluck('revenue')->toArray();

            return [
                'datasets' => [
                    [
                        'label' => '💰 Penjualan Produk (Rp)',
                        'data' => $revenueData,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'borderColor' => 'rgb(59, 130, 246)',
                        'borderWidth' => 3,
                        'fill' => true,
                        'tension' => 0.4,
                        'pointRadius' => 5,
                        'pointHoverRadius' => 7,
                        'pointBackgroundColor' => 'rgb(59, 130, 246)',
                        'pointBorderColor' => '#fff',
                        'pointBorderWidth' => 2,
                        'yAxisID' => 'y',
                    ],
                    [
                        'label' => '📦 Jumlah Pesanan',
                        'data' => $days->pluck('orders')->toArray(),
                        'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                        'borderColor' => 'rgb(16, 185, 129)',
                        'borderWidth' => 3,
                        'fill' => false,
                        'tension' => 0.4,
                        'pointRadius' => 5,
                        'pointHoverRadius' => 7,
                        'pointBackgroundColor' => 'rgb(16, 185, 129)',
                        'pointBorderColor' => '#fff',
                        'pointBorderWidth' => 2,
                        'yAxisID' => 'y1',
                    ],
                ],
                'labels' => $days->pluck('label')->toArray(),
            ];
        } catch (\Exception $e) {
            // Fallback data if queries fail
            $daysInMonth = now()->endOfMonth()->day;
            return [
                'datasets' => [
                    [
                        'label' => '💰 Pendapatan (Rp)',
                        'data' => array_fill(0, $daysInMonth, 0),
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'borderColor' => 'rgb(59, 130, 246)',
                        'borderWidth' => 3,
                        'yAxisID' => 'y',
                    ],
                ],
                'labels' => collect(range(1, $daysInMonth))->map(fn($day) => now()->startOfMonth()->addDays($day - 1)->format('d M'))->toArray(),
            ];
        }
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(0, 0, 0, 0.05)',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Tanggal',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(59, 130, 246, 0.1)',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Penjualan Produk (Rp)',
                        'color' => 'rgb(59, 130, 246)',
                        'font' => [
                            'size' => 13,
                            'weight' => 'bold',
                        ],
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Pesanan',
                        'color' => 'rgb(16, 185, 129)',
                        'font' => [
                            'size' => 13,
                            'weight' => 'bold',
                        ],
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 13,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'padding' => 12,
                    'titleFont' => [
                        'size' => 14,
                        'weight' => 'bold',
                    ],
                    'bodyFont' => [
                        'size' => 13,
                    ],
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->roles->pluck('name')->contains('super_admin');
    }

    public function getDescription(): string | \Illuminate\Support\HtmlString | null
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        // ✅ Total Penjualan = subtotal_items ONLY (tanpa ongkir)
        $totalRevenue = Order::where('payment_status', 'verified')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('subtotal_items');
        
        $totalOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $monthName = now()->locale('id')->translatedFormat('F Y');

        return new \Illuminate\Support\HtmlString(
            '<span class="text-sm">📅 <strong>' . $monthName . '</strong> • Total Penjualan: <strong class="text-primary-600">Rp ' . number_format($totalRevenue, 0, ',', '.') . '</strong> • Total Pesanan: <strong class="text-success-600">' . $totalOrders . '</strong></span>'
        );
    }
}