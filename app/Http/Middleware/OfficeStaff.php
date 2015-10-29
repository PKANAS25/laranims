<?php

namespace App\Http\Middleware;
use Auth;
use Session;

use Closure;

class OfficeStaff
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
        if(Auth::user()->admin_type>1)
            return $next($request);  
        
        else
        {
             Session::flash('warning', 'Tried to enter restricted area!');
              return redirect()->intended('/home'); 
        }
    }
}
