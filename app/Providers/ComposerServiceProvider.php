<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

use Auth;
use App\User;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
        // $composerCount1 = DB::table('invoices')
        //             ->select('invoices.*')
        //             ->where('deleted',0) 
        //             ->where('amount_paid','>=',0) 
        //             ->where('bank_ok',0)
        //             ->count();

       // $composerCount2 = DB::table('item_returns')
       //              ->select('item_returns.*')
       //              ->where('approval',0) 
       //              ->count(); 
        // $id = Auth::id();
        // $user = User::find($id);

        // $CallCenterManagerNotifications=0;
        // $CallCenterManagerCount1 = 0;
        
        // if($user)
        // {
        //    $CallCenterManagerCount1 = DB::table('refund_tickets')->where('call_center_agent',0)->count(); 
        // }//if(Auth::user()->hasRole('CallCenterManager'))

        // view()->composer('shared.header', function ($view) use($CallCenterManagerNotifications,$id) {
        //             $view->with('CallCenterManagerNotifications', $CallCenterManagerNotifications)
        //                  ->with('CallCenterManagerCount1', $CallCenterManagerCount1);

        //     });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
