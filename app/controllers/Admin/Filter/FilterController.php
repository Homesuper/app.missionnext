<?php


namespace MissionNext\Controllers\Admin\Filter;


use Illuminate\Support\Facades\Response;
use MissionNext\Controllers\Admin\AdminBaseController;
use MissionNext\Models\Application\Application;
use MissionNext\Models\Role\Role;

class FilterController extends  AdminBaseController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoles()
    {

        return Response::json(Role::all());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApps()
    {

        return Response::json(Application::all());
    }
} 