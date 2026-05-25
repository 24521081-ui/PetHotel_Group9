<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BypassRedirectIfAuthenticated extends RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if ($this->shouldBypass()) {
            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }

    private function shouldBypass(): bool
    {
        return app()->environment(['local', 'testing'])
            && (bool) config('app.middleware_bypass.guest');
    }
}
