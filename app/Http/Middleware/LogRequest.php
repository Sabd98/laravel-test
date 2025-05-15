<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequest
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $log = [
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'USER_ID' => $request->user()?->id,
            'STATUS_CODE' => $response->getStatusCode(),
            'IP' => $request->ip(),
            'USER_AGENT' => $request->userAgent(),
            'TIMESTAMP' => now()->toDateTimeString(),
        ];

        Log::channel('api_activity')->info(json_encode($log));

        return $response;
    }
}