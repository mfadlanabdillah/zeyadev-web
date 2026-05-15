<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class ForceHttpsProvider extends ServiceProvider
{
    public function register(): void
    {
        URL::forceScheme('https');
    }

    public function boot(): void
    {
        //
    }
}
