<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogApiRequests
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);

        $response = $next($request);

        $status = $response->getStatusCode();
        $method = $request->method();
        $path   = $request->path();
        $time   = round((microtime(true) - $start) * 1000, 2); // ms

        // Only log errors (status >= 400)
        if ($status >= 400) {
            Log::error(sprintf(
                "[%s] %s %d (%sms)",
                $method,
                $path,
                $status,
                $time
            ), [
                'error' => method_exists($response, 'getContent') ? $response->getContent() : null
            ]);
        }

        return $response;
    }
}
