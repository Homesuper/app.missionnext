<?php
/**
 * Created by PhpStorm.
 * User: nikolai
 * Date: 10.07.14
 * Time: 16:21
 */

namespace MissionNext\Api\Service\Override\Session;


class SessionServiceProvider extends  \Illuminate\Session\SessionServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->setupDefaultDriver();

        $this->registerSessionManager();

        $this->registerSessionDriver();
    }

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {

        $this->app['session'] = $this->app->share(function($app)
        {
            return new SessionManager($app);
        });
    }
} 