<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;

class BypassAuthenticate extends Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->shouldBypass()) {
            return $next($request);
        }

        return parent::handle($request, $next, ...$guards);
    }

    private function shouldBypass(): bool
    {
        return app()->environment(['local', 'testing'])
            && (bool) config('app.middleware_bypass.auth');
    }
}
