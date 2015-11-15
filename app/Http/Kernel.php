<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \App\Http\Middleware\HeaderNotifications::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'critical' =>\App\Http\Middleware\CriticalCheck::class,
        'nursery_admin' =>\App\Http\Middleware\NurseryAdmin::class,
        'OfficeStaff' =>\App\Http\Middleware\OfficeStaff::class,
        'Superman' =>\App\Http\Middleware\Superman::class,
        'UserAdd' =>\App\Http\Middleware\UserAdd::class,
        'CallCenterManager' =>\App\Http\Middleware\CallCenterManager::class,
        'CallCenterAgent' =>\App\Http\Middleware\CallCenterAgent::class,
        'StoreManagerOrView' =>\App\Http\Middleware\StoreManagerOrView::class,
        'StoreManager' =>\App\Http\Middleware\StoreManager::class,
        'BranchStore' =>\App\Http\Middleware\BranchStore::class,
    ];
}
