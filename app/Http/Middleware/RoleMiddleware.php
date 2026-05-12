<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            // Redirect to appropriate dashboard based on role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'guru' => redirect()->route('guru.dashboard'),
                'siswa' => redirect()->route('siswa.dashboard'),
                default => redirect()->route('login'),
            };
        }

        return $next($request);
    }
}
