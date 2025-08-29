<?php

// app/Http/Kernel.php - UPDATE THESE SECTIONS

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     */
    protected $middlewareAliases = [
        // ... existing middleware ...

        // ✅ NEW: Custom tracking rate limiter
        'tracking.rate.limit' => \App\Http\Middleware\TrackingRateLimiter::class,

        // ✅ NEW: IP blocker for suspicious activity
        'tracking.security' => \App\Http\Middleware\TrackingSecurityCheck::class,
    ];

    /**
     * The application's middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // ... existing web middleware ...
        ],

        // ✅ NEW: Secure tracking middleware group
        'secure_tracking' => [
            'web',
            'tracking.security',
            'tracking.rate.limit',
        ],

        // ✅ NEW: Tracking verification (stricter limits)
        'tracking_verify' => [
            'web',
            'tracking.security',
            'tracking.rate.limit:verify',
            'throttle:5,1', // Built-in Laravel throttle as backup
        ],
    ];
}

// ===============================================
// 🛡️ SECURITY MIDDLEWARE
// ===============================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackingSecurityCheck
{
    /**
     * ✅ Block known malicious IPs and bot traffic
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent() ?? '';

        // ✅ Check if IP is auto-blocked
        if (Cache::has("blocked_ip_{$ip}")) {
            Log::warning('Blocked IP attempted tracking access', [
                'ip' => $ip,
                'user_agent' => $userAgent,
                'url' => $request->url()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // ✅ Block obvious bot traffic
        $botPatterns = [
            '/bot/i', '/crawler/i', '/spider/i', '/scraper/i',
            '/curl/i', '/wget/i', '/python/i', '/requests/i'
        ];

        foreach ($botPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $this->logBotAccess($request);
                return response()->json([
                    'success' => false,
                    'message' => 'Bot access not allowed'
                ], 403);
            }
        }

        // ✅ Check for empty or suspicious user agents
        if (empty($userAgent) || strlen($userAgent) < 10) {
            $this->logSuspiciousAccess($request, 'empty_user_agent');
        }

        return $next($request);
    }

    private function logBotAccess(Request $request)
    {
        Log::warning('Bot traffic blocked on tracking', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'referer' => $request->header('referer')
        ]);
    }

    private function logSuspiciousAccess(Request $request, string $reason)
    {
        Log::info('Suspicious tracking access', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'reason' => $reason,
            'url' => $request->url()
        ]);
    }
}

// ===============================================
// 🔗 UPDATED ROUTES WITH MIDDLEWARE
// ===============================================

/*
routes/web.php - UPDATED with middleware protection:

Route::group([
    'prefix' => 'track',
    'as' => 'orders.track.',
    'middleware' => 'secure_tracking'
], function() {

    // ✅ Secure token tracking
    Route::get('{orderNumber}/{token}', [OrderController::class, 'trackWithToken'])
        ->name('secure')
        ->middleware('tracking.rate.limit:token')
        ->where('orderNumber', 'DP-\d{8}-\d{3}')
        ->where('token', '[a-f0-9]{64}');

    // ✅ Verification form
    Route::get('{orderNumber}', [OrderController::class, 'showTrackingVerification'])
        ->name('verify')
        ->where('orderNumber', 'DP-\d{8}-\d{3}');

    // ✅ Email verification (strictest limits)
    Route::post('{orderNumber}/verify', [OrderController::class, 'verifyTracking'])
        ->name('verify.process')
        ->middleware('tracking_verify')
        ->where('orderNumber', 'DP-\d{8}-\d{3}');

    // ✅ Verified access
    Route::get('{orderNumber}/verified', [OrderController::class, 'trackVerified'])
        ->name('verified')
        ->middleware('tracking.rate.limit:verified')
        ->where('orderNumber', 'DP-\d{8}-\d{3}');
});

*/
