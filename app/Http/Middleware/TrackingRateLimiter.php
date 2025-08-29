<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackingRateLimiter
{
    /**
     * ✅ Advanced rate limiting for tracking endpoints
     */
    public function handle(Request $request, Closure $next, string $type = 'default'): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // ✅ Different limits based on endpoint type
        $limits = $this->getLimitsConfig($type);

        // ✅ Multiple rate limiting keys for granular control
        $keys = [
            "tracking_rate_{$type}_{$ip}" => $limits['per_ip'],
            "tracking_rate_{$type}_global_{$ip}" => $limits['global_per_ip'],
        ];

        // ✅ Check suspicious patterns
        if ($this->isSuspiciousActivity($request, $type)) {
            $this->logSuspiciousActivity($request, $type, 'suspicious_pattern');
            return $this->createRateLimitResponse($limits['per_ip']['minutes']);
        }

        foreach ($keys as $key => $limit) {
            $attempts = Cache::get($key, 0);

            if ($attempts >= $limit['max']) {
                $this->logRateLimitHit($request, $type, $key, $attempts);
                return $this->createRateLimitResponse($limit['minutes']);
            }

            // ✅ Increment counter with sliding window
            Cache::put($key, $attempts + 1, now()->addMinutes($limit['minutes']));
        }

        // ✅ Log legitimate access
        $this->logTrackingAttempt($request, $type);

        return $next($request);
    }

    /**
     * ✅ Rate limiting configuration per endpoint type
     */
    private function getLimitsConfig(string $type): array
    {
        return match($type) {
            'token' => [
                'per_ip' => ['max' => 50, 'minutes' => 60],      // 50 token access per hour per IP
                'global_per_ip' => ['max' => 200, 'minutes' => 1440], // 200 per day per IP
            ],
            'verify' => [
                'per_ip' => ['max' => 10, 'minutes' => 60],      // 10 verify attempts per hour per IP
                'global_per_ip' => ['max' => 20, 'minutes' => 1440],  // 20 per day per IP
            ],
            'verified' => [
                'per_ip' => ['max' => 30, 'minutes' => 60],      // 30 verified access per hour
                'global_per_ip' => ['max' => 100, 'minutes' => 1440], // 100 per day
            ],
            default => [
                'per_ip' => ['max' => 20, 'minutes' => 60],
                'global_per_ip' => ['max' => 50, 'minutes' => 1440],
            ]
        };
    }

    /**
     * ✅ Detect suspicious activity patterns
     */
    private function isSuspiciousActivity(Request $request, string $type): bool
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // ✅ Pattern 1: Sequential order number scanning
        if ($type === 'token' || $type === 'verify') {
            $orderNumber = $request->route('orderNumber');
            $recentAttempts = Cache::get("tracking_recent_orders_{$ip}", []);

            if (count($recentAttempts) >= 5) {
                // Check if trying multiple sequential order numbers
                $numbers = array_map(function($order) {
                    return (int) substr($order, -3);  // Extract last 3 digits
                }, $recentAttempts);

                sort($numbers);
                $isSequential = $this->isSequentialPattern($numbers);

                if ($isSequential) {
                    return true;
                }
            }

            // Add current attempt to recent attempts
            $recentAttempts[] = $orderNumber;
            $recentAttempts = array_slice($recentAttempts, -10); // Keep last 10
            Cache::put("tracking_recent_orders_{$ip}", $recentAttempts, now()->addHours(2));
        }

        // ✅ Pattern 2: Bot-like user agent
        $suspiciousAgents = ['curl', 'wget', 'python', 'bot', 'crawler', 'spider'];
        $userAgentLower = strtolower($userAgent ?? '');

        foreach ($suspiciousAgents as $suspicious) {
            if (str_contains($userAgentLower, $suspicious)) {
                return true;
            }
        }

        // ✅ Pattern 3: Too many different order attempts from same IP
        $uniqueOrdersKey = "tracking_unique_orders_{$type}_{$ip}";
        $uniqueOrders = Cache::get($uniqueOrdersKey, []);

        if (count($uniqueOrders) >= 20) { // 20 different orders per day is suspicious
            return true;
        }

        return false;
    }

    /**
     * ✅ Check if numbers follow sequential pattern (brute force indicator)
     */
    private function isSequentialPattern(array $numbers): bool
    {
        if (count($numbers) < 3) return false;

        $sequential = 0;
        for ($i = 1; $i < count($numbers); $i++) {
            if ($numbers[$i] === $numbers[$i-1] + 1) {
                $sequential++;
            }
        }

        // If more than 50% are sequential, it's suspicious
        return ($sequential / count($numbers)) > 0.5;
    }

    /**
     * ✅ Create rate limit response
     */
    private function createRateLimitResponse(int $retryAfterMinutes): Response
    {
        $retryAfter = $retryAfterMinutes * 60; // Convert to seconds

        return response()->json([
            'success' => false,
            'message' => "Terlalu banyak percobaan. Coba lagi dalam {$retryAfterMinutes} menit.",
            'retry_after' => $retryAfter
        ], 429)->header('Retry-After', $retryAfter);
    }

    /**
     * ✅ Log rate limit hits for monitoring
     */
    private function logRateLimitHit(Request $request, string $type, string $key, int $attempts)
    {
        Log::warning('Tracking rate limit exceeded', [
            'type' => $type,
            'key' => $key,
            'attempts' => $attempts,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'order_number' => $request->route('orderNumber'),
            'timestamp' => now()
        ]);
    }

    /**
     * ✅ Log suspicious activity for security monitoring
     */
    private function logSuspiciousActivity(Request $request, string $type, string $reason)
    {
        Log::warning('Suspicious tracking activity detected', [
            'type' => $type,
            'reason' => $reason,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'order_number' => $request->route('orderNumber'),
            'referer' => $request->header('referer'),
            'timestamp' => now()
        ]);

        // ✅ Increment suspicious activity counter
        $suspiciousKey = "suspicious_activity_" . $request->ip();
        $count = Cache::increment($suspiciousKey, 1);
        Cache::expire($suspiciousKey, now()->addHours(24));

        // ✅ Auto-block if too many suspicious activities
        if ($count >= 10) {
            $blockKey = "blocked_ip_" . $request->ip();
            Cache::put($blockKey, true, now()->addHours(24));

            Log::critical('IP auto-blocked due to suspicious activity', [
                'ip' => $request->ip(),
                'suspicious_count' => $count,
                'timestamp' => now()
            ]);
        }
    }

    /**
     * ✅ Log normal tracking attempts for analytics
     */
    private function logTrackingAttempt(Request $request, string $type)
    {
        // Only log periodically to avoid log spam
        if (rand(1, 10) === 1) { // Log 10% of attempts
            Log::info('Tracking access attempt', [
                'type' => $type,
                'ip' => $request->ip(),
                'order_number' => $request->route('orderNumber'),
                'timestamp' => now()
            ]);
        }
    }
}
