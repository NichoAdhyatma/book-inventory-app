<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $max_attempts = 60, $decay_minutes = 1): Response
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->limiter->tooManyAttempts($key, $max_attempts)) {
            return $this->buildResponse($key, $max_attempts);
        }

        $this->limiter->hit($key, $decay_minutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $max_attempts,
            $this->calculateRemainingAttempts($key, $max_attempts)
        );
    }

    /**
     * Resolve request signature.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        return sha1(
            $request->method() .
            '|' . $request->server('SERVER_NAME') .
            '|' . $request->path() .
            '|' . $request->ip()
        );
    }

    /**
     * Create a 'too many attempts' response.
     */
    protected function buildResponse($key, $max_attempts): Response
    {
        $retry_after = $this->limiter->availableIn($key);

        return response()->json([
            'message' => 'Too many attempts. Please try again later.',
            'retry_after' => $retry_after
        ], 429)->withHeaders([
            'X-RateLimit-Limit' => $max_attempts,
            'X-RateLimit-Remaining' => 0,
            'Retry-After' => $retry_after,
        ]);
    }

    /**
     * Add the limit headers to the response.
     */
    protected function addHeaders(Response $response, $max_attempts, $remaining_attempts): Response
    {
        return $response->withHeaders([
            'X-RateLimit-Limit' => $max_attempts,
            'X-RateLimit-Remaining' => $remaining_attempts,
        ]);
    }

    /**
     * Calculate the remaining attempts.
     */
    protected function calculateRemainingAttempts($key, $max_attempts): int
    {
        return $max_attempts - $this->limiter->attempts($key) + 1;
    }
}