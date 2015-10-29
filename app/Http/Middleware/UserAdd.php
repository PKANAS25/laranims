<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
class UserAdd
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
        if(Auth::user()->hasRole('user_add'))
            return $next($request);  
        
        else
        {
             Session::flash('warning', 'Tried to enter restricted area!');
              return redirect()->intended('/home'); 
        }
    }
}
