<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ProPayments
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
         if(Auth::user()->hasRole('ProPayments'))
            return $next($request);  
        
        else
        {
              
              return redirect()->intended('/home')->with('warning', 'Tried to enter restricted area!');
        }
    }
}
