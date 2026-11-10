<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Admins have their own identity provider; a User role or token name is insufficient.
        abort_unless($request->user() instanceof Admin, 403);

        return $next($request);
    }
}
