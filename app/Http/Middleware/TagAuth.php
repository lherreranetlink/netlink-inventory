<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class TagAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->session()->has('user-barcode')&&!$request->session()->has('user-id')&&!$request->session()->has('user-email')){
            return redirect('/tag-user/login');
        }
        return $next($request);
    }
}
