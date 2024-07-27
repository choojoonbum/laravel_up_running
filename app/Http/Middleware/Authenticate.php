<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
/*
    // 파라미터를 받는 라우트 미들웨어 정의하기
    public function handle($request, Closure $next, ...$guards)
    {
        if (auth()->check() && auth()->user()->hasRole($guards)) {
            return $next($request);
        }
        return route('login');
    }
*/
}
