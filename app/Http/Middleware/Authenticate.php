<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Arr;

class Authenticate extends Middleware
{
    protected $guards;

    public function handle($request, Closure $next, ...$guards)
    {
        $this->guards = $guards;

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            if (Arr::first($this->guards) === 'user')
                return route('login.form');

            return route('login.form');
        }

        abort(response()->json([
            'endpointName' => app('request')->route()->getName(),
            'is_success' => false,
            'status_code' => 401,
            'message' => "Unauthenticated, please login first",
        ], 401));
    }
}
