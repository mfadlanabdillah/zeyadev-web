<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        URL::forceScheme('https');
    }

    public function boot(): void
    {
        $host = request()->getHost();
        $isNgrok = str_contains($host, 'ngrok-free.app') || str_contains($host, 'ngrok.io');
        
        if ($isNgrok) {
            Vite::createAssetPathsUsing(function (string $path, bool $secure) {
                $base = rtrim(config('app.url'), '/') . '/build/';
                return $base . ltrim($path, '/');
            });
        }
        
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer', 'JWT')
                );
            })
            ->withOperationTransformers(function (Operation $operation, RouteInfo $routeInfo) {
                $middlewares = $routeInfo->route->middleware();
                
                if (! in_array('auth:sanctum', $middlewares)) {
                    $operation->security = [];
                }
            });
    }
}
