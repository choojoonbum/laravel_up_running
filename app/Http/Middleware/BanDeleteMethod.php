<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BanDeleteMethod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->method() === 'DELETE') {
            return response('DELETE 메서드는 사용할 수 없습니다.', 405);
        }

        $response = $next($request);

        $response->cookie('visited-our-site', true);

        return $response;
    }
}
