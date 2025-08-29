<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // ===============================================
    // 🔐 SECURE TRACKING METHODS
    // ===============================================

    /**
     * ✅ SECURE: Track with token (primary method)
     * URL: /track/{orderNumber}/{token}
     */
    public function trackWithToken(Request $request, $orderNumber, $token)
    {
        try {
            // ✅ Rate limiting per IP
            $this->applyTrackingRateLimit($request, 'token');

            $order = Order::with(['customer', 'items.product'])
                ->where('order_number', $orderNumber)
                ->firstOrFail();

            // ✅ Validate token
            if (!$order->isValidTrackingToken($token)) {
                $this->logSuspiciousActivity($request, $orderNumber, 'invalid_token');
                abort(404, 'Tracking link tidak valid atau sudah expired');
            }

            // ✅ Log legitimate tracking access for audit
            $this->logTrackingAccess($request, $order, 'token');

            $trackingSteps = $this->getTrackingSteps($order);

            // ✅ Show LIMITED info for security (no pricing, no full address)
            return view('orders.track-secure', [
                'order' => $order,
                'trackingSteps' => $trackingSteps,
                'hasFullAccess' => false, // Limited view
                'accessMethod' => 'token'
            ]);

        } catch (\Exception $e) {
            $this->logSuspiciousActivity($request, $orderNumber, 'token_error');
            abort(404, 'Order tidak ditemukan');
        }
    }

    /**
     * ✅ FALLBACK: Redirect to verification (untuk old orders atau lost token)
     * URL: /track/{orderNumber}
     */
    public function trackRedirect($orderNumber)
    {
        // Check if order exists first (without showing details)
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            abort(404, 'Order tidak ditemukan');
        }

        // ✅ NEW ORDERS: Must use secure token
        if ($order->hasSecureTracking()) {
            return view('orders.track-verify', [
                'orderNumber' => $orderNumber,
                'message' => 'Order ini memerlukan link tracking dari email konfirmasi. Atau verifikasi dengan email Anda:',
                'isSecureOrder' => true
            ]);
        }

        // ✅ OLD ORDERS: Allow email verification (backward compatibility)
        return view('orders.track-verify', [
            'orderNumber' => $orderNumber,
            'message' => 'Masukkan email untuk melihat tracking order:',
            'isSecureOrder' => false
        ]);
    }

    /**
     * ✅ EMAIL VERIFICATION: For fallback access
     * POST: /track/{orderNumber}/verify
     */
    public function verifyTracking(Request $request, $orderNumber)
    {
        try {
            // ✅ Strict rate limiting for verification
            $this->applyTrackingRateLimit($request, 'verify');

            $validatedData = $request->validate([
                'email' => 'required|email|max:255',
            ]);

            $order = Order::with(['customer', 'items.product'])
                ->where('order_number', $orderNumber)
                ->whereHas('customer', function($query) use ($validatedData) {
                    $query->where('email', $validatedData['email']);
                })
                ->first();

            if (!$order) {
                // ✅ Log failed verification attempts
                $this->logSuspiciousActivity($request, $orderNumber, 'invalid_email', $validatedData['email']);

                return back()->withErrors([
                    'email' => 'Order tidak ditemukan atau email tidak sesuai'
                ])->withInput();
            }

            // ✅ Grant temporary verified access (1 hour)
            session(['verified_tracking_access' => [
                'order_number' => $order->order_number,
                'customer_email' => $validatedData['email'],
                'expires_at' => now()->addHour() // Only 1 hour for security
            ]]);

            // ✅ Log successful verification
            $this->logTrackingAccess($request, $order, 'email_verified');

            return redirect()->route('orders.track.verified', $orderNumber);

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            $this->logSuspiciousActivity($request, $orderNumber, 'verify_error');
            return back()->with('error', 'Terjadi kesalahan sistem')->withInput();
        }
    }

    /**
     * ✅ VERIFIED ACCESS: After email verification
     * URL: /track/{orderNumber}/verified
     */
    public function trackVerified(Request $request, $orderNumber)
    {
        // ✅ Check verified session
        if (!$this->hasVerifiedAccess($orderNumber)) {
            return redirect()->route('orders.track.verify', $orderNumber)
                ->with('error', 'Session expired, silakan verifikasi ulang');
        }

        $order = Order::with(['customer', 'items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $trackingSteps = $this->getTrackingSteps($order);

        // ✅ Show MORE info for verified users (but still not full pricing details)
        return view('orders.track-verified', [
            'order' => $order,
            'trackingSteps' => $trackingSteps,
            'hasFullAccess' => true, // Can upload payment, cancel order
            'accessMethod' => 'email_verified'
        ]);
    }

    // ===============================================
    // 🛡️ SECURITY & RATE LIMITING
    // ===============================================

    /**
     * ✅ Apply rate limiting based on access method
     */
    private function applyTrackingRateLimit(Request $request, string $method)
    {
        $ip = $request->ip();

        $limits = [
            'token' => ['max' => 20, 'minutes' => 60],      // 20 token access per hour
            'verify' => ['max' => 5, 'minutes' => 60],       // 5 verify attempts per hour
        ];

        $limit = $limits[$method];
        $key = "tracking_rate_limit_{$method}_{$ip}";

        $attempts = Cache::get($key, 0);

        if ($attempts >= $limit['max']) {
            \Log::warning("Rate limit exceeded for tracking", [
                'ip' => $ip,
                'method' => $method,
                'attempts' => $attempts,
                'user_agent' => $request->userAgent()
            ]);

            abort(429, 'Terlalu banyak percobaan. Coba lagi dalam ' . $limit['minutes'] . ' menit.');
        }

        Cache::put($key, $attempts + 1, now()->addMinutes($limit['minutes']));
    }

    /**
     * ✅ Log tracking access for audit
     */
    private function logTrackingAccess(Request $request, Order $order, string $method)
    {
        \Log::info('Order tracking accessed', [
            'order_number' => $order->order_number,
            'customer_email' => $order->customer->email,
            'access_method' => $method,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
            'user_id' => Auth::id()
        ]);
    }

    /**
     * ✅ Log suspicious activity
     */
    private function logSuspiciousActivity(Request $request, $orderNumber, string $reason, $email = null)
    {
        \Log::warning('Suspicious tracking activity', [
            'order_number' => $orderNumber,
            'reason' => $reason,
            'email_attempted' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
            'referer' => $request->header('referer')
        ]);
    }

    /**
     * ✅ Check if user has verified access via email
     */
    private function hasVerifiedAccess($orderNumber): bool
    {
        $access = session('verified_tracking_access');

        return $access &&
               $access['order_number'] === $orderNumber &&
               now()->lt($access['expires_at']);
    }

    // ===============================================
    // 📋 FULL ORDER MANAGEMENT (Authenticated Users)
    // ===============================================

    public function index()
    {
        $query = Order::with(['customer', 'items.product'])
            ->orderBy('created_at', 'desc');

        if (Auth::check()) {
            $customerIds = Customer::where('user_id', Auth::id())->pluck('id');
            $query->whereIn('customer_id', $customerIds);
        } else {
            return redirect()->route('login')->with('message', 'Silakan login untuk melihat pesanan Anda');
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($orderNumber)
    {
        $order = Order::with(['customer', 'items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // ✅ ENHANCED: Check multiple access levels
        if (!$this->canAccessOrderFull($order)) {
            // Try to redirect to appropriate tracking method
            if ($order->hasSecureTracking()) {
                return redirect()->route('orders.track.verify', $orderNumber)
                    ->with('info', 'Gunakan link tracking dari email konfirmasi atau verifikasi dengan email');
            }

            return redirect()->route('orders.track.verify', $orderNumber)
                ->with('error', 'Silakan verifikasi dengan email untuk melihat detail pesanan');
        }

        return view('orders.show', compact('order'));
    }

    public function uploadPaymentProof(Request $request, $orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();

            // ✅ ENHANCED: Check access permission (full access required for payment upload)
            if (!$this->canAccessOrderFull($order) && !$this->hasVerifiedAccess($orderNumber)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke pesanan ini'
                    ], 403);
                }
                return redirect()->route('orders.track.verify', $orderNumber)
                    ->with('error', 'Silakan verifikasi dengan email untuk upload bukti pembayaran');
            }

            // Only allow upload if status is pending_payment AND no existing proof
            if ($order->status !== Order::STATUS_PENDING_PAYMENT || $order->payment_proof) {
                $message = $order->payment_proof
                    ? 'Bukti pembayaran sudah pernah diupload. Jika ada masalah, silakan hubungi customer service kami.'
                    : 'Upload bukti pembayaran hanya dapat dilakukan untuk pesanan yang belum dibayar';

                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return redirect()->back()->with('error', $message);
            }

            $validatedData = $request->validate([
                'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
                'payment_notes' => 'nullable|string|max:500',
            ]);

            $file = $request->file('payment_proof');
            $filename = $order->order_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $paymentProofPath = $file->storeAs('payment-proofs', $filename, 'public');

            $uploadLog = "\n\n[UPLOAD BUKTI PEMBAYARAN - " . now()->format('d/m/Y H:i:s') . "]";
            $uploadLog .= "\nFile: " . $filename;
            $uploadLog .= "\nIP: " . $request->ip();
            if ($request->input('payment_notes')) {
                $uploadLog .= "\nCatatan: " . $request->input('payment_notes');
            }

            $order->update([
                'payment_proof' => $paymentProofPath,
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'notes' => ($order->notes ?? '') . $uploadLog
            ]);

            \Log::info('Payment proof uploaded', [
                'order_number' => $order->order_number,
                'customer_email' => $order->customer->email,
                'file_path' => $paymentProofPath,
                'ip' => $request->ip(),
                'access_method' => $this->hasVerifiedAccess($orderNumber) ? 'email_verified' : 'authenticated'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diupload! Tim kami akan memverifikasi dalam 1-3 jam kerja.'
                ]);
            }

            $redirectUrl = $this->hasVerifiedAccess($orderNumber)
                ? route('orders.track.verified', $orderNumber)
                : route('orders.show', $orderNumber);

            return redirect($redirectUrl)
                ->with('success', 'Bukti pembayaran berhasil diupload! Tim kami akan memverifikasi dalam 1-3 jam kerja.');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Data tidak valid', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Payment proof upload failed', [
                'order_number' => $orderNumber,
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem'], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran')->withInput();
        }
    }

    /**
     * ✅ VERIFICATION FORM: Show form to verify email
     * GET: /track/{orderNumber}
     */
    public function showTrackingVerification($orderNumber)
    {
        // ✅ Check if order exists (without revealing details)
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            abort(404, 'Order tidak ditemukan');
        }

        // ✅ Different messages for secure vs legacy orders
        if ($order->hasSecureTracking()) {
            $message = 'Order ini memerlukan link tracking dari email konfirmasi. Atau verifikasi dengan email Anda:';
            $helpText = 'Link tracking dikirim ke email saat order dibuat. Cek folder Spam jika tidak ditemukan.';
        } else {
            $message = 'Masukkan email untuk melihat tracking order:';
            $helpText = 'Gunakan email yang sama dengan saat membuat pesanan.';
        }

        return view('orders.track-verify', [
            'orderNumber' => $orderNumber,
            'message' => $message,
            'helpText' => $helpText,
            'isSecureOrder' => $order->hasSecureTracking()
        ]);
    }

    public function cancel(Request $request, $orderNumber)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();

            // ✅ ENHANCED: Check access (full access required for cancellation)
            if (!$this->canAccessOrderFull($order) && !$this->hasVerifiedAccess($orderNumber)) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke pesanan ini'], 403);
                }
                return redirect()->route('orders.track.verify', $orderNumber)
                    ->with('error', 'Silakan verifikasi dengan email untuk membatalkan pesanan');
            }

            if (!in_array($order->status, [Order::STATUS_PENDING_PAYMENT, Order::STATUS_PAID])) {
                $message = 'Pesanan tidak dapat dibatalkan karena sudah dalam proses produksi';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return redirect()->back()->with('error', $message);
            }

            $validatedData = $request->validate([
                'cancellation_reason' => 'required|string|max:500',
            ]);

            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'notes' => $order->notes
                    ? $order->notes . "\n\nAlasan Pembatalan: " . $validatedData['cancellation_reason']
                    : "Alasan Pembatalan: " . $validatedData['cancellation_reason']
            ]);

            // ✅ Log cancellation
            \Log::info('Order cancelled', [
                'order_number' => $order->order_number,
                'reason' => $validatedData['cancellation_reason'],
                'cancelled_by' => Auth::id() ? 'authenticated_user' : 'verified_guest',
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibatalkan'
                ]);
            }

            $redirectUrl = $this->hasVerifiedAccess($orderNumber)
                ? route('orders.track.verified', $orderNumber)
                : route('orders.show', $orderNumber);

            return redirect($redirectUrl)
                ->with('success', 'Pesanan berhasil dibatalkan. Jika Anda sudah melakukan pembayaran, tim kami akan menghubungi Anda untuk proses refund.');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Data tidak valid', 'errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan')->withInput();
        }
    }

    public function downloadDesignFile($orderNumber, $itemId)
    {
        try {
            $order = Order::where('order_number', $orderNumber)->firstOrFail();

            // ✅ ENHANCED: Check access (admin, owner, or verified guest)
            if (!$this->canAccessOrderFull($order) && !$this->hasVerifiedAccess($orderNumber) && !$this->isAdmin()) {
                abort(403, 'Anda tidak memiliki akses ke file ini');
            }

            $orderItem = $order->items()->findOrFail($itemId);

            if (!$orderItem->design_file_path || !Storage::disk('public')->exists($orderItem->design_file_path)) {
                abort(404, 'File tidak ditemukan');
            }

            $filePath = storage_path('app/public/' . $orderItem->design_file_path);
            $fileName = basename($orderItem->design_file_path);

            // ✅ Log file download
            \Log::info('Design file downloaded', [
                'order_number' => $order->order_number,
                'file_path' => $orderItem->design_file_path,
                'downloaded_by' => Auth::id() ? 'authenticated_user' : 'verified_guest',
                'ip' => request()->ip()
            ]);

            return response()->download($filePath, $fileName);

        } catch (\Exception $e) {
            abort(404, 'File tidak dapat diunduh');
        }
    }

    // ===============================================
    // 🔍 ACCESS CONTROL HELPERS
    // ===============================================

    /**
     * ✅ Check full order access (authenticated users only)
     */
    private function canAccessOrderFull(Order $order): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        return $order->customer->user_id === $user->id;
    }

    /**
     * ✅ Check if user can access order as recent guest (30 minutes after checkout)
     */
    private function canAccessOrderAsRecentGuest(Order $order): bool
    {
        $guestAccess = session('guest_order_access');

        if ($guestAccess &&
            $guestAccess['order_number'] === $order->order_number &&
            now()->lt($guestAccess['expires_at'])) {
            return true;
        }

        return false;
    }

    private function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->hasRole('admin');
    }

    private function getTrackingSteps(Order $order): array
    {
        $allSteps = [
            Order::STATUS_PENDING_PAYMENT => [
                'label' => 'Menunggu Pembayaran',
                'description' => 'Pesanan dibuat, menunggu konfirmasi pembayaran',
                'icon' => 'clock',
                'completed' => true
            ],
            Order::STATUS_PAID => [
                'label' => 'Pembayaran Dikonfirmasi',
                'description' => 'Pembayaran telah dikonfirmasi, pesanan masuk antrian produksi',
                'icon' => 'check-circle',
                'completed' => false
            ],
            Order::STATUS_PROCESSING => [
                'label' => 'Sedang Produksi',
                'description' => 'Pesanan sedang dalam proses produksi',
                'icon' => 'cog',
                'completed' => false
            ],
            Order::STATUS_READY => [
                'label' => 'Siap Kirim',
                'description' => 'Produksi selesai, pesanan siap untuk dikirim',
                'icon' => 'package',
                'completed' => false
            ],
            Order::STATUS_SHIPPED => [
                'label' => 'Dalam Pengiriman',
                'description' => 'Pesanan sedang dalam perjalanan',
                'icon' => 'truck',
                'completed' => false
            ],
            Order::STATUS_COMPLETED => [
                'label' => 'Selesai',
                'description' => 'Pesanan telah selesai dan diterima',
                'icon' => 'check-double',
                'completed' => false
            ]
        ];

        // Mark completed steps
        $statusOrder = [
            Order::STATUS_PENDING_PAYMENT,
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_READY,
            Order::STATUS_SHIPPED,
            Order::STATUS_COMPLETED
        ];

        $currentIndex = array_search($order->status, $statusOrder);

        if ($currentIndex !== false) {
            for ($i = 0; $i <= $currentIndex; $i++) {
                if (isset($allSteps[$statusOrder[$i]])) {
                    $allSteps[$statusOrder[$i]]['completed'] = true;
                }
            }
        }

        // Handle cancelled status
        if ($order->status === Order::STATUS_CANCELLED) {
            return [
                Order::STATUS_PENDING_PAYMENT => $allSteps[Order::STATUS_PENDING_PAYMENT],
                Order::STATUS_CANCELLED => [
                    'label' => 'Pesanan Dibatalkan',
                    'description' => 'Pesanan telah dibatalkan',
                    'icon' => 'x-circle',
                    'completed' => true,
                    'is_cancelled' => true
                ]
            ];
        }

        return $allSteps;
    }
}
