<?php


namespace MissionNext\Provider;

use Illuminate\Support\ServiceProvider;
use MissionNext\Routing\AdminRouting;
use MissionNext\Routing\Authorize;
use MissionNext\Routing\Routing;
use MissionNext\Routing\UploadsRouting;

class RoutingProvider extends ServiceProvider
{

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        new  Routing($this->app);
        new AdminRouting($this->app);
        new UploadsRouting($this->app);
        new Authorize($this->app);
    }

} 