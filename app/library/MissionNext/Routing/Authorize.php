<?php


namespace MissionNext\Routing;

use Illuminate\Routing\Router;

use Illuminate\Foundation\Application;
use MissionNext\Controllers\Authorize\Controller;

class Authorize {
    /** @var  Router */
    private $router;

    public function __construct(Application $App)
    {
        $this->router = $App->make('router');

        $this->router->post('authorize/callback', Controller::class.'@postIndex');

    }
} 