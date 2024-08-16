<?php
namespace MissionNext\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use MissionNext\Models\Application\Application;
use MissionNext\Models\Role\Role;
use MissionNext\Models\User\User;
use Illuminate\Support\Facades\DB;

class AjaxController extends AdminBaseController {

    public function filterBy() {

        if ($this->request->isMethod('post')) {
            $appIdArray = explode(',', Input::get('appId'));
            $roleIdArray = explode(',', Input::get('roleId'));
            $take = Input::get('take');

            if($appIdArray[0] == '') {
                $usersCount = $this->getCountByRoleUsers($roleIdArray);
                $users = $this->getFilteredByRoleUsers($roleIdArray, 0, $take);

                return View::make('admin.user.ajax.filteredUsers', array('users' => $users, 'count' => count($usersCount)));
            }

            if($roleIdArray[0] == '') {
                $usersCount = $this->getCountByAppUsers($appIdArray);
                $users = $this->getFilteredByAppUsers($appIdArray, 0, $take);

                return View::make('admin.user.ajax.filteredUsers', array('users' => $users, 'count' => count($usersCount)));
            }

            $users = $this->getFilteredUsers($appIdArray, $roleIdArray, 0, $take);
            $usersCount = $this->getCountUsers($appIdArray, $roleIdArray);

            return View::make('admin.user.ajax.filteredUsers', array('users' => $users, 'count' => count($usersCount)));
        } else {
            $response = 'fail';
        }

        return Response::make($response);
    }

    public function filterByShowMore() {

        if ($this->request->isMethod('post')) {

            $appIdArray = explode(',', Input::get('appId'));
            $roleIdArray = explode(',', Input::get('roleId'));
            $take = Input::get('take');
            $skip = Input::get('skip');

            if($appIdArray[0] == '') {
                $users = $this->getFilteredByRoleUsers($roleIdArray, $skip, $take);

                return View::make('admin.user.ajax.filteredSliceUsers', array('users' => $users));
            }

            if($roleIdArray[0] == '') {
                $users = $this->getFilteredByAppUsers($appIdArray, $skip, $take);

                return View::make('admin.user.ajax.filteredSliceUsers', array('users' => $users));
            }

            $users = $this->getFilteredUsers($appIdArray, $roleIdArray, $skip, $take);

            return View::make('admin.user.ajax.filteredSliceUsers', array('users' => $users));
        } else {
            $response = 'fail';
        }

        return Response::make($response);
    }

    public function getRolesApps()
    {
        $roles = Role::all()->toArray();
        $apps = Application::all()->toArray();
        $resultArray = array();
        $resultArray['apps'] = $apps;
        $resultArray['roles'] = $roles;

        return Response::json($resultArray);
    }

    private function getFilteredByAppUsers($appId, $skip = 0, $take = 15) {
        $users = DB::table('users')
            ->join('user_apps', 'users.id', '=', 'user_apps.user_id')
            ->whereIn('user_apps.app_id', $appId)
            ->orderBy('users.id')
            ->skip($skip)
            ->take($take)
            ->get();

        return $users;
    }

    private function getFilteredUsers($appId, $roleId, $skip = 0, $take = 15) {
        $users = DB::table('users')
            ->join('user_apps', 'users.id', '=', 'user_apps.user_id')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereIn('user_apps.app_id', $appId)
            ->whereIn('user_roles.role_id', $roleId)
            ->orderBy('users.id')
            ->skip($skip)
            ->take($take)
            ->get();

        return $users;
    }

    private function getCountUsers($appId, $roleId) {
        $users = DB::table('users')
            ->join('user_apps', 'users.id', '=', 'user_apps.user_id')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereIn('user_apps.app_id', $appId)
            ->whereIn('user_roles.role_id', $roleId)
            ->orderBy('users.id')
            ->get();

        return $users;
    }


    private function getCountByAppUsers($appId) {
        $users = DB::table('users')
            ->join('user_apps', 'users.id', '=', 'user_apps.user_id')
            ->whereIn('user_apps.app_id', $appId)
            ->orderBy('users.id')
            ->get();

        return $users;
    }

    private function getFilteredByRoleUsers($roleId, $skip = 0, $take = 15) {
        $users = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereIn('user_roles.role_id', $roleId)
            ->orderBy('users.id')
            ->skip($skip)
            ->take($take)
            ->get();

        return $users;
    }

    private function getCountByRoleUsers($roleId) {
        $users = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereIn('user_roles.role_id', $roleId)
            ->orderBy('users.id')
            ->get();

        return $users;
    }
}
