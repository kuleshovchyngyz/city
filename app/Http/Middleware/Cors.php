<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $allowedOrigins = ['*'];
        $origin = $request->header('Origin');

        // Allow all origins and set CORS headers
        $headers = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => '*',
            'Access-Control-Allow-Credentials' => 'true',
            'Vary' => 'Origin'
        ];

        // Handle preflight requests
        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 200, $headers);
        }

        $response = $next($request);

        // Add headers to the response
        foreach ($headers as $key => $value) {
            if (!empty($value)) {
                $response->headers->set($key, $value);
            }
        }

        return $response;
    }
}
