<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{


    /**
     * Download sales report as CSV
     */
    public function downloadSalesReport(Request $request)
    {
        // Validate user has admin access
        $user = Auth::user();
        if (!$user || !$user->roles->pluck('name')->contains('super_admin')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string|in:all,paid,pending,cancelled',
            'type' => 'required|string|in:orders,products,customers'
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->subMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now();
        $status = $request->status ?? 'all';
        $type = $request->type;

        switch ($type) {
            case 'orders':
                return $this->downloadOrdersReport($startDate, $endDate, $status);
            case 'products':
                return $this->downloadProductsReport($startDate, $endDate);
            case 'customers':
                return $this->downloadCustomersReport($startDate, $endDate);
            default:
                abort(400, 'Invalid report type');
        }
    }

    /**
     * Generate orders report CSV
     */
    private function downloadOrdersReport(Carbon $startDate, Carbon $endDate, string $status)
    {
        $query = Order::with(['customer', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all') {
            switch ($status) {
                case 'paid':
                    $query->where('payment_status', Order::PAYMENT_STATUS_PAID);
                    break;
                case 'pending':
                    $query->where('payment_status', Order::PAYMENT_STATUS_PENDING);
                    break;
                case 'cancelled':
                    $query->where('status', Order::STATUS_CANCELLED);
                    break;
            }
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $filename = 'laporan-pesanan-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'No. Pesanan',
                'Tanggal Pesanan',
                'Nama Pelanggan',
                'Email Pelanggan',
                'Telepon Pelanggan',
                'Alamat Pengiriman',
                'Kota Tujuan',
                'Kurir',
                'Layanan',
                'Status Pesanan',
                'Status Pembayaran',
                'Subtotal Item (Rp)',
                'Ongkir (Rp)',
                'Pajak (Rp)',
                'Total (Rp)',
                'Produk',
                'Catatan'
            ]);

            foreach ($orders as $order) {
                $products = $order->items->map(function($item) {
                    return $item->product->name . ' (Qty: ' . $item->quantity . ', @Rp' . number_format($item->price, 0, ',', '.') . ')';
                })->implode('; ');

                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer->name ?? 'N/A',
                    $order->customer->email ?? 'N/A',
                    $order->customer->phone ?? 'N/A',
                    $order->full_shipping_address,
                    $order->shipping_location,
                    $order->shipping_courier ?? 'N/A',
                    $order->shipping_service ?? 'N/A',
                    $order->status_label,
                    $order->payment_status_label,
                    $order->subtotal_items,
                    $order->shipping_cost,
                    $order->tax_amount,
                    $order->total_amount,
                    $products,
                    $order->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate products report CSV
     */
    private function downloadProductsReport(Carbon $startDate, Carbon $endDate)
    {
        $products = OrderItem::with(['product.category', 'order'])
            ->whereHas('order', function($query) use ($startDate, $endDate) {
                $query->where('payment_status', Order::PAYMENT_STATUS_PAID)
                      ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue, COUNT(*) as order_count')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->get();

        $filename = 'laporan-produk-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'Nama Produk',
                'Kategori',
                'Harga Satuan (Rp)',
                'Stok Tersedia',
                'Total Terjual',
                'Jumlah Pesanan',
                'Total Pendapatan (Rp)',
                'Rata-rata per Pesanan'
            ]);

            foreach ($products as $item) {
                $product = $item->product;
                if (!$product) continue;

                fputcsv($file, [
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $product->price,
                    $product->stock,
                    $item->total_sold,
                    $item->order_count,
                    $item->total_revenue,
                    $item->order_count > 0 ? round($item->total_sold / $item->order_count, 2) : 0
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate customers report CSV
     */
    private function downloadCustomersReport(Carbon $startDate, Carbon $endDate)
    {
        $customers = Customer::with(['orders' => function($query) use ($startDate, $endDate) {
                $query->where('payment_status', Order::PAYMENT_STATUS_PAID)
                      ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->whereHas('orders', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get()
            ->map(function($customer) {
                $customer->total_orders = $customer->orders->count();
                $customer->total_spent = $customer->orders->sum('total_amount');
                $customer->avg_order_value = $customer->total_orders > 0 ? $customer->total_spent / $customer->total_orders : 0;
                $customer->last_order_date = $customer->orders->max('created_at');
                return $customer;
            })
            ->sortByDesc('total_spent');

        $filename = 'laporan-pelanggan-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'Nama Pelanggan',
                'Email',
                'Telepon',
                'Alamat',
                'Tanggal Daftar',
                'Total Pesanan',
                'Total Belanja (Rp)',
                'Rata-rata per Pesanan (Rp)',
                'Pesanan Terakhir'
            ]);

            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    $customer->phone ?? 'N/A',
                    $customer->address ?? 'N/A',
                    $customer->created_at->format('Y-m-d'),
                    $customer->total_orders,
                    $customer->total_spent,
                    round($customer->avg_order_value, 0),
                    $customer->last_order_date ? Carbon::parse($customer->last_order_date)->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}