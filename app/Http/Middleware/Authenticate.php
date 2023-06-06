<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $token = $request->cookie('_token');
        $decToken = false;

        try {
            $decToken = Crypt::decryptString($token);
        } catch (DecryptException $e) {
            $decToken = false;
        }

        if ($decToken) {
            $request->headers->set('Authorization', 'Bearer ' . $decToken);
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}
