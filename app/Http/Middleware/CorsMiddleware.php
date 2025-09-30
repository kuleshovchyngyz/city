<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        \Log::info('CORS Middleware hit', [
            'origin' => $request->header('Origin'),
            'method' => $request->method(),
            'path' => $request->path()
        ]);
        $allowedOrigins = [
            'https://test.vinz.ru',
            'https://vinz.ru',
            'https://www.test.vinz.ru',
            'http://localhost:5173'
        ];

        $origin = $request->header('Origin');

        if (in_array($origin, $allowedOrigins)) {
            $headers = [
                'Access-Control-Allow-Origin'      => $origin,
                'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, X-CSRF-TOKEN, Accept',
                'Access-Control-Allow-Credentials' => 'true',
                'Vary' => 'Origin'
            ];

            if ($request->isMethod('OPTIONS')) {
                return response()->json('OK', 200, $headers);
            }

            $response = $next($request);

            foreach($headers as $key => $value) {
                $response->headers->set($key, $value);
            }

            return $response;
        }

        return $next($request);
    }
}
