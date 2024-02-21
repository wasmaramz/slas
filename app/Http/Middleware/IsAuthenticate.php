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
		if (!session()->has('sess_user_id')) 
			return redirect('/logout');
		else
			return $next($request);
		/*
		 * ADA MASA CHECK BALIK, SEPATUTNYA CHECK SEMUA SESSION KAT BAWAH TAPI TAK MENJADI, JADI PAKAI YANG ATAS DULU ......
		if ( 
			!session()->has('sess_user_id') OR !session()->has('sess_user_fullname') OR !session()->has('sess_user_name') OR 
			!session()->has('sess_level_id') OR !session()->has('sess_level_name') OR !session()->has('sess_user_status') OR 
			!session()->has('sess_user_email') OR !session()->has('sess_user_nophone') OR Auth::check()
		) {
			return redirect('/logout');
		}
		else {
			return $next($request);
		}
		 */
    }
}
