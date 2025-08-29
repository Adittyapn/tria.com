<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function index()
    {
        $checkoutData = $this->prepareCheckoutData();

        if ($checkoutData['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $userData = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? ''
            ];
        }

        return view('checkout.index', [
            'items' => $checkoutData['items'],
            'subtotal' => $checkoutData['subtotal'],
            'estimatedWeight' => $checkoutData['estimatedWeight'],
            'userData' => $userData,
            'isBuyNow' => session()->has('buy_now'),
        ]);
    }

    public function process(Request $request)
    {
        $validatedCustomer = $this->validateCustomerData($request);
        $validatedShipping = $request->validate([
            'shipping_province_id' => 'required|integer',
            'shipping_city_id' => 'required|integer',
            'shipping_district_id' => 'nullable|integer',
            'shipping_province_name' => 'required|string|max:255',
            'shipping_city_name' => 'required|string|max:255',
            'shipping_district_name' => 'nullable|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_courier' => 'required|string|max:50',
            'shipping_service' => 'required|string|max:100',
            'shipping_etd' => 'nullable|string|max:50',
            'shipping_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $checkoutData = $this->prepareCheckoutData();

            if ($checkoutData['items']->isEmpty()) {
                throw ValidationException::withMessages(['cart' => 'Tidak ada item untuk di-checkout.']);
            }

            $order = DB::transaction(function () use ($checkoutData, $validatedCustomer, $validatedShipping) {
                $customer = $this->createOrUpdateCustomer($validatedCustomer);
                $order = $this->createOrder($customer, $checkoutData, $validatedShipping);

                // ✅ SECURITY: Generate tracking token for new orders
                $order->assignTrackingToken();

                foreach ($checkoutData['items'] as $item) {
                    if ($item instanceof \App\Models\Cart) {
                        OrderItem::createFromCart($item, $order->id);
                    } else {
                        $orderItem = OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'price' => $item->unit_price,
                            'subtotal' => $item->subtotal,
                            'custom_size_width' => $item->custom_size_width,
                            'custom_size_height' => $item->custom_size_height,
                            'selected_material' => $item->selected_material,
                            'selected_finishing' => $item->selected_finishing,
                            'design_notes' => $item->design_notes,
                            'requires_design_service' => $item->requires_design_service,
                        ]);

                        if (!empty($item->design_file) && Storage::disk('public')->exists($item->design_file)) {
                            try {
                                $newPath = "designs/orders/{$order->order_number}/" . basename($item->design_file);
                                Storage::disk('public')->move($item->design_file, $newPath);
                                $orderItem->update(['design_file_path' => $newPath]);
                            } catch (\Exception $e) {
                                \Log::warning("Failed to move design file: " . $e->getMessage());
                            }
                        }
                    }
                }

                $order->updateTotalAmount();

                if (session()->has('buy_now')) {
                    $buyNowData = session('buy_now');
                    if (!empty($buyNowData['design_file']) && Storage::disk('public')->exists($buyNowData['design_file'])) {
                        Storage::disk('public')->delete($buyNowData['design_file']);
                    }
                    session()->forget('buy_now');
                } else {
                    Cart::clearCart($this->getUserId(), $this->getSessionId());
                }

                return $order;
            });

            // ✅ SECURITY: Enhanced guest session with secure tracking access
            if (!Auth::check()) {
                session(['guest_order_access' => [
                    'order_number' => $order->order_number,
                    'customer_email' => $order->customer->email,
                    'tracking_token' => $order->tracking_token,  // ✅ NEW: Store token for immediate access
                    'expires_at' => now()->addMinutes(30)  // ✅ Reduced to 30 minutes for security
                ]]);
            }

            // ✅ SECURITY: Log order creation for audit
            \Log::info('Order created', [
                'order_number' => $order->order_number,
                'customer_email' => $order->customer->email,
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'has_tracking_token' => !empty($order->tracking_token)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat!',
                'redirect_url' => $order->hasSecureTracking()
                    ? $order->getSecureTrackingUrl()  // ✅ NEW: Use secure tracking URL
                    : route('orders.show', $order->order_number)
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            // ✅ SECURITY: Log checkout errors for monitoring
            \Log::error('Checkout process failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            report($e);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function buyNow(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'custom_size_width' => 'nullable|numeric|min:0.1|max:1000',
            'custom_size_height' => 'nullable|numeric|min:0.1|max:1000',
            'selected_material' => 'nullable|string|max:255',
            'selected_finishing' => 'nullable|array',
            'selected_finishing.*' => 'string|max:255',
            'design_notes' => 'nullable|string|max:1000',
            'design_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,ai,psd,eps|max:' . ($product->max_file_size_mb * 1024),
            'requires_design_service' => 'boolean',
        ]);

        if (isset($validated['selected_finishing'])) {
            $validated['selected_finishing'] = is_array($validated['selected_finishing'])
                ? implode(', ', $validated['selected_finishing'])
                : $validated['selected_finishing'];
        }

        $tempFilePath = null;
        if ($request->hasFile('design_file')) {
            try {
                $tempFilePath = $request->file('design_file')->store('temp_designs', 'public');
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload file: ' . $e->getMessage()
                ], 500);
            }
        }

        session(['buy_now' => [
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'custom_size_width' => $validated['custom_size_width'] ?? null,
            'custom_size_height' => $validated['custom_size_height'] ?? null,
            'selected_material' => $validated['selected_material'] ?? null,
            'selected_finishing' => $validated['selected_finishing'] ?? null,
            'design_notes' => $validated['design_notes'] ?? null,
            'requires_design_service' => $validated['requires_design_service'] ?? false,
            'design_file' => $tempFilePath,
        ]]);

        return response()->json([
            'success' => true,
            'redirect_url' => route('checkout.index')
        ]);
    }

    // ===============================================
    // API METHODS (Unchanged)
    // ===============================================

    public function getCities(Request $request)
    {
        try {
            $provinceId = $request->input('province_id');

            if (!$provinceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Province ID is required',
                    'cities' => []
                ], 400);
            }

            $cities = $this->rajaOngkirService->getCities($provinceId);

            return response()->json([
                'success' => true,
                'cities' => $cities,
                'count' => count($cities)
            ]);

        } catch (\Exception $e) {
            \Log::error('CheckoutController: getCities error', [
                'province_id' => $request->input('province_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota',
                'cities' => []
            ], 500);
        }
    }

    public function getDistricts(Request $request)
    {
        try {
            $cityId = $request->input('city_id');

            if (!$cityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'City ID is required',
                    'districts' => []
                ], 400);
            }

            $districts = $this->rajaOngkirService->getDistricts($cityId);

            return response()->json([
                'success' => true,
                'districts' => $districts,
                'count' => count($districts)
            ]);

        } catch (\Exception $e) {
            \Log::error('CheckoutController: getDistricts error', [
                'city_id' => $request->input('city_id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kecamatan',
                'districts' => []
            ], 500);
        }
    }

    public function calculateShipping(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'destination_district_id' => 'nullable|integer',
                'destination_city_id' => 'required|integer',
                'weight' => 'required|numeric|min:1',
                'courier' => 'nullable|string|in:jne,pos,tiki,all'
            ]);

            $destinationId = $validatedData['destination_district_id'] ?? $validatedData['destination_city_id'];
            $useDistrict = !empty($validatedData['destination_district_id']);

            $originId = config('services.rajaongkir.origin_district_id')
                       ?? config('services.rajaongkir.origin_city_id', 574);

            $weight = max($validatedData['weight'], 1);
            $courier = $validatedData['courier'] ?? 'all';

            \Log::info('CheckoutController: Calculating shipping', [
                'origin' => $originId,
                'destination' => $destinationId,
                'weight' => $weight,
                'courier' => $courier,
                'use_district' => $useDistrict
            ]);

            $shippingOptions = $this->rajaOngkirService->getShippingCost(
                $originId,
                $destinationId,
                $weight * 1000,
                $courier === 'all' ? null : $courier
            );

            return response()->json([
                'success' => true,
                'shipping_options' => $shippingOptions,
                'calculation_info' => [
                    'origin_id' => $originId,
                    'destination_id' => $destinationId,
                    'weight_kg' => $weight,
                    'using_district' => $useDistrict
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('CheckoutController: Shipping calculation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===============================================
    // HELPER METHODS
    // ===============================================

    private function getUserId()
    {
        return Auth::id();
    }

    private function getSessionId()
    {
        return session()->getId();
    }

    private function validateCustomerData(Request $request): array
    {
        if (Auth::check()) {
            return $request->validate([
                'customer_name' => 'nullable|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'required|string|max:500',
            ]);
        } else {
            return $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'required|string|max:500',
            ]);
        }
    }

    private function createOrUpdateCustomer(array $customerData): Customer
    {
        if (Auth::check()) {
            $user = Auth::user();

            return Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name'    => $customerData['customer_name'] ?? $user->name,
                    'email'   => $customerData['customer_email'] ?? $user->email,
                    'phone'   => $customerData['customer_phone'] ?? $user->phone,
                    'address' => $customerData['customer_address'],
                ]
            );
        }

        return Customer::create([
            'user_id' => null,
            'name'    => $customerData['customer_name'],
            'email'   => $customerData['customer_email'],
            'phone'   => $customerData['customer_phone'] ?? null,
            'address' => $customerData['customer_address'],
        ]);
    }

    private function createOrder(Customer $customer, $checkoutData, array $shippingData): Order
    {
        $subtotal = $checkoutData['subtotal'];
        $tax = $subtotal * 0.11; // 11% PPN

        return Order::create([
            'customer_id' => $customer->id,
            'order_number' => Order::generateOrderNumber(),
            'subtotal_items' => $subtotal,
            'shipping_cost' => $shippingData['shipping_cost'],
            'tax_amount' => $tax,
            'total_amount' => $subtotal + $shippingData['shipping_cost'] + $tax,
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
            'notes' => $shippingData['notes'] ?? null,

            'shipping_province_id' => $shippingData['shipping_province_id'],
            'shipping_city_id' => $shippingData['shipping_city_id'],
            'shipping_district_id' => $shippingData['shipping_district_id'] ?? null,
            'shipping_province_name' => $shippingData['shipping_province_name'],
            'shipping_city_name' => $shippingData['shipping_city_name'],
            'shipping_district_name' => $shippingData['shipping_district_name'] ?? null,
            'shipping_address' => $shippingData['shipping_address'] ?? $customer->address,
            'shipping_courier' => $shippingData['shipping_courier'],
            'shipping_service' => $shippingData['shipping_service'],
            'shipping_etd' => $shippingData['shipping_etd'],
        ]);
    }

    private function calculateEstimatedWeight($cartItems): float
    {
        $totalWeight = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $weight = $product->estimated_weight_per_unit ?? 0.5;

            if ($item->custom_size_width && $item->custom_size_height) {
                $area = ($item->custom_size_width / 100) * ($item->custom_size_height / 100);
                $weight *= $area;
            }

            $totalWeight += ($weight * $item->quantity);
        }

        return max($totalWeight, 1);
    }

    private function calculateUnitPrice(Product $product, array $data): float
    {
        $priceDetails = $product->calculatePrice([
            'quantity' => $data['quantity'],
            'custom_size' => [
                'width' => ($data['custom_size_width'] ?? 0) / 100,
                'length' => ($data['custom_size_height'] ?? 0) / 100
            ],
            'material' => $data['selected_material'] ?? null,
            'finishing' => is_string($data['selected_finishing'])
                ? explode(', ', $data['selected_finishing'])
                : [],
            'design_service' => $data['requires_design_service'] ?? false
        ]);

        if (in_array($product->pricing_type, ['per_meter_square', 'per_meter_linear'])) {
            return $priceDetails['unit_price'] * $priceDetails['size_multiplier'];
        }

        return $priceDetails['unit_price'];
    }

    private function calculateEstimatedWeightBuyNow(Product $product, array $data): float
    {
        $weight = $product->estimated_weight_per_unit ?? 0.5;

        if (!empty($data['custom_size_width']) && !empty($data['custom_size_height'])) {
            $area = ($data['custom_size_width'] / 100) * ($data['custom_size_height'] / 100);
            $weight *= $area;
        }

        return max($weight * $data['quantity'], 1);
    }

    private function prepareCheckoutData(): array
    {
        $items = new \Illuminate\Support\Collection();

        if ($buyNowData = session('buy_now')) {
            $product = Product::findOrFail($buyNowData['product_id']);
            $unitPrice = $this->calculateUnitPrice($product, $buyNowData);

            $item = (object) [
                'product_id' => $product->id,
                'quantity' => $buyNowData['quantity'],
                'custom_size_width' => $buyNowData['custom_size_width'] ?? null,
                'custom_size_height' => $buyNowData['custom_size_height'] ?? null,
                'selected_material' => $buyNowData['selected_material'] ?? null,
                'selected_finishing' => $buyNowData['selected_finishing'] ?? null,
                'design_notes' => $buyNowData['design_notes'] ?? null,
                'requires_design_service' => $buyNowData['requires_design_service'] ?? false,
                'design_file' => $buyNowData['design_file'] ?? null,
                'unit_price' => $unitPrice,
                'subtotal' => $unitPrice * $buyNowData['quantity'],
                'product' => $product,
            ];
            $items->push($item);
            $subtotal = $items->sum('subtotal');
            $estimatedWeight = $this->calculateEstimatedWeightBuyNow($product, $buyNowData);

        } else {
            $userId = $this->getUserId();
            $sessionId = $this->getSessionId();
            $items = Cart::getCartItems($userId, $sessionId);
            $subtotal = Cart::getTotalAmount($userId, $sessionId);
            $estimatedWeight = $this->calculateEstimatedWeight($items);
        }

        return compact('items', 'subtotal', 'estimatedWeight');
    }
}
