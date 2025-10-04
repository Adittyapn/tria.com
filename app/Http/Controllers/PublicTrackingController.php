<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicTrackingController extends Controller
{
    public function index(): View
    {
        return view('public.track-order');
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'order_number' => [
                'required',
                'string',
                'regex:/^DP-\d{8}-\d{3}$/'
            ],
        ], [
            'order_number.regex' => 'Format nomor pesanan tidak valid. Contoh: DP-20251002-001',
        ]);

        $order = Order::where('order_number', $validated['order_number'])->first();

        if (!$order) {
            return back()->with('error', 'Nomor pesanan tidak ditemukan.')->withInput();
        }

        // Gunakan alur verifikasi/secure tracking yang sudah ada
        return redirect()->route('orders.track.verify', $validated['order_number']);
    }

    public function getTrackingSteps(Order $order): array
    {
        $steps = [
            [
                'status' => Order::STATUS_PENDING_PAYMENT,
                'label' => 'Menunggu Pembayaran',
                'description' => 'Pesanan menunggu pembayaran',
                'icon' => 'clock',
                'color' => 'warning',
                'completed' => in_array($order->status, [
                    Order::STATUS_PAID,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_READY,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $order->status === Order::STATUS_PENDING_PAYMENT,
                'date' => $order->created_at,
            ],
            [
                'status' => Order::STATUS_PAID,
                'label' => 'Pembayaran Terverifikasi',
                'description' => 'Pembayaran telah dikonfirmasi',
                'icon' => 'check-circle',
                'color' => 'success',
                'completed' => in_array($order->status, [
                    Order::STATUS_PROCESSING,
                    Order::STATUS_READY,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $order->status === Order::STATUS_PAID,
                'date' => $order->payment_status === Order::PAYMENT_STATUS_PAID ? $order->updated_at : null,
            ],
            [
                'status' => Order::STATUS_PROCESSING,
                'label' => 'Dalam Produksi',
                'description' => 'Pesanan sedang diproduksi',
                'icon' => 'cog',
                'color' => 'info',
                'completed' => in_array($order->status, [
                    Order::STATUS_READY,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $order->status === Order::STATUS_PROCESSING,
                'date' => null,
            ],
            [
                'status' => Order::STATUS_READY,
                'label' => 'Siap Dikirim',
                'description' => 'Pesanan siap untuk dikirim',
                'icon' => 'archive-box',
                'color' => 'primary',
                'completed' => in_array($order->status, [
                    Order::STATUS_SHIPPED,
                    Order::STATUS_COMPLETED
                ]),
                'active' => $order->status === Order::STATUS_READY,
                'date' => null,
            ],
            [
                'status' => Order::STATUS_SHIPPED,
                'label' => 'Dalam Pengiriman',
                'description' => 'Pesanan sedang dalam perjalanan',
                'icon' => 'truck',
                'color' => 'warning',
                'completed' => $order->status === Order::STATUS_COMPLETED,
                'active' => $order->status === Order::STATUS_SHIPPED,
                'date' => $order->shipped_at,
            ],
            [
                'status' => Order::STATUS_COMPLETED,
                'label' => 'Selesai',
                'description' => 'Pesanan telah sampai',
                'icon' => 'check-badge',
                'color' => 'success',
                'completed' => $order->status === Order::STATUS_COMPLETED,
                'active' => $order->status === Order::STATUS_COMPLETED,
                'date' => $order->delivered_at,
            ],
        ];

        return $steps;
    }
}