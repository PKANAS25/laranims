<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CriticalCheck
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
        if(Auth::user()->admin_type>1 && Auth::user()->branch==0 )
            return redirect()->intended('branching'); 

        elseif(Auth::user()->admin_type==1 && Auth::user()->branch==0 )
            return redirect()->intended('errorLogout'); 
        
        else
        return $next($request);
    }
}
