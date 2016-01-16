<?php

namespace BFACP\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \BFACP\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \BFACP\Http\Middleware\VerifyCsrfToken::class,
        ],
        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => \BFACP\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => \BFACP\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'   => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'role'       => \Zizaco\Entrust\Middleware\EntrustRole::class,
        'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
        'ability'    => \Zizaco\Entrust\Middleware\EntrustAbility::class,
    ];

    public function bootstrap()
    {
        parent::bootstrap();

        if(Config::get('bfacp.site.ssl')) {
            $this->middleware[] = \BFACP\Http\Middleware\Secure::class;
        }
    }
}