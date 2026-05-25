<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CustomerApiTokenMiddleware
{
    private const TOKEN_CACHE_PREFIX = 'customer_api_token:';

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! $token) {
            if (app()->environment(['local', 'testing']) && config('app.middleware_bypass.customer_api_token')) {
                return $next($request);
            }

            return response()->json([
                'message' => 'Vui long gui Bearer token.',
            ], 401);
        }

        $userId = Cache::get(self::TOKEN_CACHE_PREFIX.$token);

        if (! $userId) {
            return response()->json([
                'message' => 'Token khong hop le hoac da het han.',
            ], 401);
        }

        $user = User::with('customer')->find($userId);

        if (! $user || ! $user->customer || ! $user->is_active) {
            return response()->json([
                'message' => 'Tai khoan customer khong hop le.',
            ], 403);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
