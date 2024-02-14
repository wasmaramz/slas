<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if ( 
			$request->session()->has('sess_user_id') AND $request->session()->has('sess_user_name') AND $request->session()->has('sess_level_id') AND 
			$request->session()->has('sess_level_name') AND $request->session()->has('sess_user_status') AND $request->session()->has('sess_user_email') AND 
			$request->session()->has('sess_user_nophone') AND Auth::check()
		) {
			return $next($request);
		}
		else {
			return route('logout');
		}
    }
}
