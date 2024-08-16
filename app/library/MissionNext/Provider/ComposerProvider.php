<?php

namespace MissionNext\Provider;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;
use MissionNext\Composers\ApplicationComposer;
use MissionNext\Controllers\Admin\Subscription\SubConfigController;

class ComposerProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        /** @var  $view Factory */
        $view = $this->app->make('view');
       //$this->app->view->composer('xxxxxxxxx', ApplicationComposer::class );
        $view->composer([
                         SubConfigController::VIEW_PREFIX.'.create',
                         SubConfigController::VIEW_PREFIX.'.index',
                         SubConfigController::VIEW_PREFIX.'.edit',
                         SubConfigController::VIEW_PREFIX.'.management',
                         'admin.menu.menu',

                        ], ApplicationComposer::class);
    }

} 