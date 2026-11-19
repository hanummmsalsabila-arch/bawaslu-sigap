<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserOnly
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->isAdmin()) {
            abort(403, 'Halaman ini khusus petugas.');
        }

        return $next($request);
    }
}