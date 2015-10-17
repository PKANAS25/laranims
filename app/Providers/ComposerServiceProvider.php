<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
        $composerCount1 = DB::table('invoices')
                    ->select('invoices.*')
                    ->where('deleted',0) 
                    ->where('amount_paid','>=',0) 
                    ->where('bank_ok',0)
                    ->count();

       $composerCount2 = DB::table('item_returns')
                    ->select('item_returns.*')
                    ->where('approval',0) 
                    ->count(); 


        view()->composer('shared.header', function ($view) use($composerCount1,$composerCount2) {
                    $view->with('composerCount1', $composerCount1)
                         ->with('composerCount2', $composerCount2);

            });
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
