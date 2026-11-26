<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Wpuszcza tylko moderatora i administratora
class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !($user->isAdmin() || $user->isModerator())) {
            abort(403);
        }

        return $next($request);
    }
}
