<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminForDocs
{
    public function handle(Request $request, Closure $next)
    {
        $guard = auth()->guard('web');
        $user = $guard->user();

        if (! $user || ! $user->hasAnyRole(['super_admin'])) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}