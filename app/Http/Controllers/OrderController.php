<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // TODO: Get orders for current user
        // $orders = Order::where('user_id', auth()->id())->latest()->get();
        
        // Dummy data for now
        $orders = [
            [
                'id' => 'ORD123456',
                'date' => '2025-08-21',
                'total' => 145000,
                'status' => 'pending',
                'payment_status' => 'waiting_verification',
                'items' => [
                    [
                        'name' => 'Spanduk Vinyl 1x2m',
                        'quantity' => 2,
                        'price' => 60000,
                        'image' => 'https://via.placeholder.com/80',
                    ]
                ]
            ],
            [
                'id' => 'ORD123455',
                'date' => '2025-08-20',
                'total' => 250000,
                'status' => 'processing',
                'payment_status' => 'verified',
                'items' => [
                    [
                        'name' => 'X-Banner 60x160',
                        'quantity' => 1,
                        'price' => 150000,
                        'image' => 'https://via.placeholder.com/80',
                    ],
                    [
                        'name' => 'Kartu Nama Premium',
                        'quantity' => 100,
                        'price' => 100000,
                        'image' => 'https://via.placeholder.com/80',
                    ]
                ]
            ],
        ];

        return view('orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        // TODO: Get specific order details
        // $order = Order::findOrFail($orderId);
        
        // Dummy data for now
        $order = [
            'id' => 'ORD123456',
            'date' => '2025-08-21',
            'total' => 145000,
            'status' => 'pending',
            'payment_status' => 'waiting_verification',
            'shipping_status' => 'pending',
            'shipping_method' => 'JNE Regular',
            'shipping_cost' => 25000,
            'shipping_address' => [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 123',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12345',
            ],
            'payment' => [
                'method' => 'Bank Transfer BCA',
                'amount' => 145000,
                'proof' => 'payment-proofs/example.jpg',
                'status' => 'waiting_verification',
            ],
            'items' => [
                [
                    'name' => 'Spanduk Vinyl 1x2m',
                    'quantity' => 2,
                    'price' => 60000,
                    'image' => 'https://via.placeholder.com/80',
                ]
            ]
        ];

        return view('orders.show', compact('order'));
    }
}
