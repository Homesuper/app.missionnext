<?php
namespace MissionNext\Provider;

use Illuminate\Support\ServiceProvider;
use MissionNext\Api\Auth\Listener;
use MissionNext\Api\Auth\Manager;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Api\Auth\Token;
use Illuminate\Support\Facades\Route;
use MissionNext\Filter\RoleChecker;
use MissionNext\Filter\RouteSecurityFilter;

class SecurityProvider extends ServiceProvider
{
    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventProvider::class);

        $this->app->singleton('rest.token', function () {

            return new Token();
        });

        $this->app->singleton('rest.manager', function () {

            return new Manager();
        });

        $this->app->singleton('rest.listener', function ($app) {

            return new Listener($app->make('rest.manager'));
        });

        $this->app->bind('security_context', function($app){

            return (new SecurityContext())->setToken($app->make('rest.token'));

        });

        $this->app->bind('rest.listener.sync', function ($app) {
            $instance = $app->make('rest.listener');
            $instance->setRequest($app->make('request'));

            return $instance;
        });


        $this->filters();
    }

    /**
     * @return $this
     */
    protected function filters()
    {
        Route::filter(RouteSecurityFilter::ROLE, RouteSecurityFilter::class.'@'.RouteSecurityFilter::ROLE_M);
        Route::filter(RouteSecurityFilter::AUTHORIZE,  RouteSecurityFilter::class.'@'.RouteSecurityFilter::AUTHORIZE_M);
        Route::filter(RouteSecurityFilter::ROLE_ADMIN_AREA,  RouteSecurityFilter::class.'@'.RouteSecurityFilter::ROLE_ADMIN_AREA);
        Route::filter(RoleChecker::CHECK,  RoleChecker::class.'@'.RoleChecker::CHECK);

        return $this;
    }

} 