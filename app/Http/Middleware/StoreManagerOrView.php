<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class StoreManagerOrView
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
         if(Auth::user()->hasRole('StoreManager') || Auth::user()->hasRole('StoreView'))
            return $next($request);  
        
        else
        {
              
              return redirect()->intended('/home')->with('warning', 'Tried to enter restricted area!');
        }
    }
}
