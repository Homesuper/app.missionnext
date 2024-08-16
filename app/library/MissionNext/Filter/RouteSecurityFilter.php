<?php

namespace MissionNext\Filter;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Route as Router;
use MissionNext\Api\Exceptions\SecurityContextException;
use MissionNext\Facade\SecurityContext;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User as UserModel;
use Illuminate\Http\Request as LRequest;
use MissionNext\Provider\EventProvider;
use MissionNext\Routing\Routing;

class RouteSecurityFilter
{

    const AUTHORIZE = 'authorize';
    const AUTHORIZE_M = 'authorize';
    const ROLE = 'role';
    const ROLE_ADMIN_AREA = 'adminAreaRole';
    const ROLE_M = 'role';

    static public $ALLOWED_ROLES = [BaseDataModel::AGENCY, BaseDataModel::CANDIDATE, BaseDataModel::ORGANIZATION, BaseDataModel::JOB];

    /**
     * @param Router $route
     * @param LRequest $request
     * @throws \MissionNext\Api\Exceptions\SecurityContextException
     */
    public function role(Router $route, LRequest $request)
    {
        $symfRequest = $request->request;
        if ( $symfRequest->has("_method") && strtoupper($symfRequest->get("_method")) === "PUT" ){
             $symfRequest->remove("_method");
        }

        $user_id = Route::input('user', Route::input('profile', null));
        $role = Route::input('type');
        $job_id = Route::input('job');

        $user = $user_id ? UserModel::findOrFail($user_id) : null;
        if ($user) {
            $role = $user->role();
        }

        $role = $route->getName() === Routing::ROUTE_CREATE_USER ? $request->request->get("role") : $role;
        $role = $route->getName() === Routing::ROUTE_CREATE_JOB ? BaseDataModel::JOB : $role;
        $role = $job_id ? BaseDataModel::JOB : $role;

        if ($role) {
            if (static::isAllowedRole($role)) {
                SecurityContext::getToken()->setRoles([$role]);
            } else {

                throw new SecurityContextException("'$role' role doesn't exists", SecurityContextException::ON_SET_ROLE);
            }
        }
    }

    /**
     * @param Router $route
     * @param LRequest $request
     * @throws \MissionNext\Api\Exceptions\SecurityContextException
     */
    public function adminAreaRole(Router $route, LRequest $request)
    {
        $this->role($route, $request);
        SecurityContext::getFacadeRoot()->setIsAdminArea(true);
    }

    public function authorize(Router $route, $request)
    {
        App::make("rest.listener.sync")->handle();
    }

    /**
     * @param $role
     *
     * @return bool
     */
    public static function isAllowedRole($role)
    {

        return in_array($role, static::$ALLOWED_ROLES);
    }

} 