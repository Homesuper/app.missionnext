<?php


namespace MissionNext\Routing;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use MissionNext\Controllers\Uploads\FileController;

class UploadsRouting {

    public function __construct(Application $App)
    {
       /** @var  $router Router */
       $router =  $App->make('router');

       $router->get('/profile/file/{fileName}', FileController::class.'@getFile');
       $router->controller('/uploads/{fileName}', FileController::class) ;
    }
} 