<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsLibrarian
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
        //bejelentkezett felhasználó: Auth::user()
        //0 admin
        //1 konyvtaros
        //2 felhasznalo
        if (Auth::user() && Auth::user()->permission == 1) { 
            return $next($request); 
        } 
        return redirect('dashboard')->with('error','You have not admin access');
    }
}
