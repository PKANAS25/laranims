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
        $TotalNotifications=0;

         
        $CallCenterManagerCallUnassigns = 0;
        $CallCenterAgentCallAssigns = 0;

        $NotDepositedCount =0;
        $NotDepositedChequeCount=0;

        $StoreRequestsCount=0;
        $StoreReturnsCount=0;
        $StoreRejectionsCount =0;

        $StoreTransferCount=0;
        $ReturnRejectionsCount=0;

        $payrollContentRejectionsCount=0;

        
        if ($request->is("/") || $request->is("password/email") || $request->is("/logout") || $request->is("/errorLogout") ||  $request->is("password/reset/*"))
           {
                    return $next($request);
           } 

       elseif (Auth::check())
       { 
        
        if(Auth::user()->hasRole('CallCenterManager'))
        {
           $CallCenterManagerCallUnassigns = DB::table('refund_tickets')->where('call_center_agent',0)->where('subscription_id','>',0)->count(); 
           $TotalNotifications+=$CallCenterManagerCallUnassigns;

           
        }//if(Auth::user()->hasRole('CallCenterManager'))

//-------------------------------------------------------------------------------------------------------------------------------------
         if(Auth::user()->hasRole('CallCenterAgent'))
        {
           $CallCenterAgentCallAssigns = DB::table('refund_tickets')->where('call_center_agent',Auth::id())->whereNull('review')->count(); 
           $TotalNotifications+=$CallCenterAgentCallAssigns;

           
        }//if(Auth::user()->hasRole('CallCenterManager'))

//-------------------------------------------------------------------------------------------------------------------------------------
        if(Auth::user()->hasRole('PaymentsDeposit'))
        {
         $NotDepositedCount = DB::table('invoices')->select('invoices.*')->where('deleted',0) ->where('amount_paid','>=',0)->where('bank_ok',0)->count();
         $NotDepositedChequeCount = DB::table('invoices')->select('invoices.*')->where('deleted',0) ->where('amount_paid','>=',0)->where('bank_ok',0)->where('cheque',1)->count();
         $TotalNotifications+=$NotDepositedCount;
        }
//-------------------------------------------------------------------------------------------------------------------------------------
        if(Auth::user()->hasRole('StoreManager'))
        {
             
         $StoreRequestsCount = DB::table('store_requests')->where('read_status',0)->count();
         $TotalNotifications+=$StoreRequestsCount;

         $StoreReturnsCount = DB::table('item_returns')->where('approval',0)->count();
         $TotalNotifications+=$StoreReturnsCount;

         $StoreRejectionsCount = DB::table('item_transfers')->where('approval',-1)->where('reject_read',0)->count();
         $TotalNotifications+=$StoreRejectionsCount;
        }
//-------------------------------------------------------------------------------------------------------------------------------------
         if(Auth::user()->hasRole('BranchStore'))
        {
             
          
         $StoreTransferCount = DB::table('item_transfers')->where('approval',0)->where('branch',Auth::user()->branch)->count();
         $TotalNotifications+=$StoreTransferCount;

         $ReturnRejectionsCount = DB::table('item_returns')->where('approval',-1)->where('reject_read',0)->count();
         $TotalNotifications+=$ReturnRejectionsCount;
        }
//-------------------------------------------------------------------------------------------------------------------------------------
        $payrollContentRejectionsCount=DB::table('bonus')->where('approval',-1)->where('reject_read',0)->where('admin',Auth::id())->count();

        $TotalNotifications+=$payrollContentRejectionsCount;
//-------------------------------------------------------------------------------------------------------------------------------------        

            view()->composer('shared.header', function ($view) 
            use($TotalNotifications,$CallCenterManagerCallUnassigns,$NotDepositedCount,$NotDepositedChequeCount,$StoreRequestsCount,$StoreReturnsCount,
                $StoreRejectionsCount,$CallCenterAgentCallAssigns,$StoreTransferCount,$ReturnRejectionsCount) 
                {
                           $view->with('TotalNotifications', $TotalNotifications)

                          ->with('CallCenterManagerCallUnassigns', $CallCenterManagerCallUnassigns)

                          ->with('CallCenterAgentCallAssigns', $CallCenterAgentCallAssigns)

                          ->with('NotDepositedChequeCount', $NotDepositedChequeCount)
                          ->with('NotDepositedCount', $NotDepositedCount)

                          ->with('StoreRequestsCount', $StoreRequestsCount)
                          ->with('StoreReturnsCount', $StoreReturnsCount)
                          ->with('StoreRejectionsCount', $StoreRejectionsCount)

                          ->with('StoreTransferCount', $StoreTransferCount)
                          ->with('ReturnRejectionsCount', $ReturnRejectionsCount);
                });

        return $next($request);
      }

      else
         return $next($request);
    }
}
