<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // TODO: Get cart items and calculate totals
        return view('checkout.index');
    }

    public function payment()
    {
        // TODO: Validate shipping info before showing payment page
        return view('checkout.payment');
    }

    public function confirmation()
    {
        // TODO: Process payment and create order
        return view('checkout.confirmation');
    }

    public function store(Request $request)
    {
        // Validate shipping information
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'postal_code' => 'required|string|max:10',
            'shipping_method' => 'required|string',
        ]);

        // TODO: Save shipping information and redirect to payment page
        return redirect()->route('checkout.payment');
    }

    public function processPayment(Request $request)
    {
        // Validate payment proof upload
        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // max 10MB
        ]);

        if ($request->hasFile('payment_proof')) {
            // Store the payment proof
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            // TODO: Save order and payment information to database
            // $order = Order::create([...]);
            // $payment = Payment::create([
            //     'order_id' => $order->id,
            //     'proof_image' => $path,
            //     'status' => 'pending',
            // ]);

            // TODO: Send notification to admin about new payment proof
            // TODO: Clear cart after successful order

            return redirect()->route('checkout.confirmation')->with('success', 'Bukti pembayaran berhasil diunggah');
        }

        return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran');
    }
}
