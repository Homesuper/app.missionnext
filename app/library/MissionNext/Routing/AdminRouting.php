<?php

namespace MissionNext\Routing;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use MissionNext\Controllers\Admin\Administrator;
use MissionNext\Controllers\Admin\Filter\FilterController;
use MissionNext\Controllers\Admin\Server;
use MissionNext\Controllers\Admin\Subscription\Ajax\SubscriptionController as SubscriptionControllerAjax;
use MissionNext\Controllers\Admin\Subscription\CouponController;
use MissionNext\Controllers\Admin\Subscription\SubConfigController;
use MissionNext\Controllers\Admin\Subscription\Ajax\SubConfigController as SubConfigControllerAjax;
use MissionNext\Controllers\Admin\Subscription\Ajax\UserController as AjaxAdminController;

class AdminRouting
{
    /** @var  Router */
    private $router;

    public function __construct(Application $App)
    {
        $this->router = $App->make('router');

        Route::get('rematch/user', array(
            'as'    => 'rematchSingleUser',
            'uses'  => Server::class.'@rematch'
        ));

        Route::get('deploy/{server}', array(
            'as'        => 'deployAction',
            'uses'      => Server::class.'@deploy'
        ));

        Route::get('/', array(
            'as' => 'homepage',
            function () {
                return Redirect::route('login');
            }
        ));

        Route::match(array('GET', 'POST'), 'login', array(
            'as' => 'login',
            'uses' => 'MissionNext\Controllers\Admin\AdminBaseController@login'
        ));

        Route::group(array('prefix' => 'dashboard', "before" => ["admin_auth"]), function () {

            Route::get('logout', array(
                'as' => 'logout',
                function () {
                    Sentry::logout();
                    return Redirect::route('login');
                }
            ));
            Route::get('/', array(
                'as' => 'adminHomepage',
                function () {
                    return View::make('admin.adminHomepage');
                }
            ));

            // ================ AJAX ===========================
            Route::group(array('prefix' => 'ajax'), function () {


                // ------------------- Users -----------------------
                Route::get('user/list', array(
                    'as' => 'ajaxUserList',
                    'uses' => AjaxAdminController::class . '@getList'
                ));

                Route::get('user/{user}', array(
                    'as' => 'ajaxUser',
                    'uses' => AjaxAdminController::class . '@getIndex'
                ))->where('user', '[0-9]+');
                Route::get('user/{status}/{user}', array(
                    'as' => 'ajaxStatusUser',
                    'uses' => AjaxAdminController::class . '@setStatus'
                ))->where(['user' => '[0-9]+', 'status' => '(enable|disable)']);

                Route::get('user/app/{status}/{user}/{app}', array(
                    'as' => 'ajaxSetAppStatus',
                    'uses' => AjaxAdminController::class . '@setAppStatus'
                ))->where(['user' => '[0-9]+', 'status' => '(enable|disable)']);
                // ------------------- END Users -----------------------


                // ------------------- Subscription -----------------------

                $this->router->controller('subscription/config', SubConfigControllerAjax::class,
                    [
                        'getIndex' => 'ajax.sub.config.list',
                    ]);

                $this->router->get('subscription/manager/{user}', SubscriptionControllerAjax::class.'@getIndex')->where(['user' => '[0-9]+']);
                $this->router->get('subscription/manager/transactions/{user}', SubscriptionControllerAjax::class.'@getTransactions')->where(['user' => '[0-9]+']);
                $this->router->put('subscription/{subscription}', SubscriptionControllerAjax::class.'@updateIndex')->where(['user' => '[0-9]+']);
                // ------------------- END Subscription -----------------------

                // ------------ FILTER --------------------------------
                $this->router->get('filter/roles', FilterController::class.'@getRoles');
                $this->router->get('filter/apps', FilterController::class.'@getApps');



                //-------------------- END FILTER -------------------------

            });
            // ================ END AJAX ===========================


            // ================ SUBSCRIPTIONS ===========================
            Route::delete('subscription/coupon/{id}', array('as' => 'sub.coupon.delete', 'uses' => 'MissionNext\Controllers\Admin\Subscription\CouponController@deleteIndex'));

            Route::group(array('prefix' => 'subscription'), function () {

                $this->router->controller('config', SubConfigController::class,
                    [
                        'getIndex' => 'sub.config.list',
                        'getCreate' => 'sub.config.create',
                        'postIndex' => 'sub.config.new',
                        'getEdit' => 'sub.config.edit',
                        'postEdit' => 'sub.config.update',
                        'deleteIndex' => 'sub.config.delete',
                        'getManagement' => 'sub.config.management',

                    ]);

                $this->router->controller('coupon', CouponController::class,
                    [
                        'getIndex' => 'sub.coupon.list',
                        'getCreate' => 'sub.coupon.create',
                        'postIndex' => 'sub.coupon.new',
                        'getEdit' => 'sub.coupon.edit',
                        'postEdit' => 'sub.coupon.update',
                        //'deleteIndex' => 'sub.coupon.delete',

                    ]);
            });


            // ================ END SUBSCRIPTIONS ========================


            // -----------   Applications ----------------------
            Route::get('/application', array(
                'as' => 'applications',
                'uses' => 'MissionNext\Controllers\Admin\ApplicationController@index'
            ));
            Route::match(array('GET', 'POST'), '/application/create', array(
                'as' => 'applicationCreate',
                'uses' => 'MissionNext\Controllers\Admin\ApplicationController@create'
            ));
            Route::match(array('GET', 'POST'), '/application/{id}/edit', array(
                'as' => 'applicationEdit',
                'uses' => 'MissionNext\Controllers\Admin\ApplicationController@edit'
            ));
            Route::match(array('GET', 'DELETE'), '/application/{id}/delete', array(
                'as' => 'applicationDelete',
                'uses' => 'MissionNext\Controllers\Admin\ApplicationController@delete'
            ));
            // -------------------------------------------------


            // ------------------- Users -----------------------

            Route::get('/user', array(
                'as' => 'users',
                'uses' => 'MissionNext\Controllers\Admin\UserController@index'
            ));
            Route::get('/user/profile/{user}', array(
                'as' => 'userProfile',
                'uses' => 'MissionNext\Controllers\Admin\UserController@profile'
            ));
            Route::match(array('GET', 'POST'), '/user/create', array(
                'as' => 'userCreate',
                'uses' => 'MissionNext\Controllers\Admin\UserController@create'
            ));
            Route::match(array('GET', 'POST'), '/user/{id}/edit', array(
                'as' => 'userEdit',
                'uses' => 'MissionNext\Controllers\Admin\UserController@edit'
            ));
            Route::match(array('GET', 'DELETE'), '/user/{id}/delete', array(
                'as' => 'userDelete',
                'uses' => 'MissionNext\Controllers\Admin\UserController@delete'
            ));
            Route::match(array('POST'), '/user/searching', array(
                'as' => 'userSearching',
                'uses' => 'MissionNext\Controllers\Admin\UserController@searching'
            ));
            Route::match(array('GET'), '/user/{searchText}/search', array(
                'as' => 'search',
                'uses' => 'MissionNext\Controllers\Admin\UserController@search'
            ));

            //------------------- Admin Users --------------------------------

            Route::delete('administrator/{id}', array('as' => 'administrator.delete', 'uses' => 'MissionNext\Controllers\Admin\Administrator@deleteIndex'));

             $this->router->controller('administrator', Administrator::class,
                 [
                     'getIndex' => 'administrator.list',
                     'getCreate' => 'administrator.create',
                     'postIndex' => 'administrator.new',
                     'getEdit' => 'administrator.edit',
                     'postEdit' => 'administrator.update',
                     //'deleteIndex' => 'administrator.delete',

                 ]);


            //------------------- End Admin Users ---------------------------

            // -------------------------------------------------
            // ------------------- Language --------------------
            Route::get('/language', array(
                'as' => 'languages',
                'uses' => 'MissionNext\Controllers\Admin\LanguageController@index'
            ));
            Route::match(array('GET', 'POST'), '/language/create', array(
                'as' => 'languageCreate',
                'uses' => 'MissionNext\Controllers\Admin\LanguageController@create'
            ));
            Route::match(array('GET', 'POST'), '/language/{id}/edit', array(
                'as' => 'languageEdit',
                'uses' => 'MissionNext\Controllers\Admin\LanguageController@edit'
            ));
            Route::match(array('GET', 'DELETE'), '/language/{id}/delete', array(
                'as' => 'languageDelete',
                'uses' => 'MissionNext\Controllers\Admin\LanguageController@delete'
            ));
            // -------------------------------------------------
            // --------------- Filters -------------------------
            Route::match(array('POST'), '/user/filterBy', array(
                'as' => 'userFilters',
                'uses' => 'MissionNext\Controllers\Admin\AjaxController@filterBy'
            ));
            Route::match(array('POST'), '/user/filterByApps', array(
                'as' => 'filteredUsersByEth',
                'uses' => 'MissionNext\Controllers\Admin\AjaxController@filterByShowMore'
            ));
            Route::match(array('POST'), '/getrolesapps', array(
                'as' => 'getRoles',
                'uses' => 'MissionNext\Controllers\Admin\AjaxController@getRolesApps'
            ));
            // -------------------------------------------------


        }); // end group dashboard

    } // end construct
} 