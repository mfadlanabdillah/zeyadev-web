<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        if (str_contains($host, 'ngrok-free.app') || str_contains($host, 'ngrok.io')) {
            $request->setTrustedProxies([$request->getClientIp()], Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
            URL::forceScheme('https');
        }
        
        return $next($request);
    }
}
