<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use DB;

class HeaderNotifications
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
        
        $CallCenterManagerNotifications=0;
        $CallCenterManagerCount1 = 0;
        
        if(Auth::user()->hasRole('CallCenterManager'))
        {
           $CallCenterManagerCount1 = DB::table('refund_tickets')->where('call_center_agent',0)->count(); 
           $CallCenterManagerNotifications+=$CallCenterManagerCount1;

           
        }//if(Auth::user()->hasRole('CallCenterManager'))

        
        view()->composer('shared.header', function ($view) use($CallCenterManagerNotifications,$CallCenterManagerCount1) {
                     $view->with('CallCenterManagerNotifications', $CallCenterManagerNotifications)
                          ->with('CallCenterManagerCount1', $CallCenterManagerCount1);
                         });

        return $next($request);
    }
}
