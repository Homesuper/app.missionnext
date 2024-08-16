<?php
namespace MissionNext\Controllers\Admin;

use Cartalyst\Sentry\Sentry;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use MissionNext\Models\CacheData\UserCachedData;
use MissionNext\Models\Role\Role;
use MissionNext\Models\User\User;
use MissionNext\Models\Application\Application;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Repos\CachedData\UserCachedRepositoryInterface;

class UserController extends AdminBaseController {

    /**
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        $users = User::orderBy('id')->paginate(AdminBaseController::PAGINATE);

        return View::make('admin.user.users', array(
            'users' => $users,
        ));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function profile($userId) {

        /** @var  $repo UserCachedRepository */
        $repo = $this->repoContainer[UserCachedRepositoryInterface::KEY];
        $user = $repo->findOrFail($userId)->getData();

        $field_keys = array_keys($user['profileData']);

        $filename_prefix = $user['role'].$userId.'_';

        $field_keys = array_map(function($item){
            return "'{$item}'";
        }, $field_keys);

        $model_id = null;
        switch($user['role']) {
            case 'candidate':
                $model_id = 9;
                break;
            case 'organization':
            case 'agency':
                $model_id = 10;
                break;
        }

        $sortedKeys = $fieldLabels = [];
        $fields_str = implode(',', $field_keys);
        if (!empty($fields_str)) {
            $whereIn = "WHERE fm.symbol_key IN ({$fields_str})";

            $query = "SELECT fm.symbol_key, fm.name FROM form_models fm, app_forms af {$whereIn} and fm.data_model_id = ".$model_id." and fm.form_id=af.id and af.name='profile' ORDER BY fm.display_order ASC";
            $ordererFields = DB::select($query);
            $sortedKeys = $fieldLabels = [];
            foreach ($ordererFields as $ordererField) {
                $sortedKeys[] = $ordererField->symbol_key;
                $fieldLabels[$ordererField->symbol_key] = $ordererField->name;
            }
            $sortedKeys = array_unique($sortedKeys);
        }

        return $this->view->make('admin.user.profile', array(

            'user'              => $user,
            'sortedKeys'        => $sortedKeys,
            'fieldLabels'       => $fieldLabels,
            'uploadFieldPrefix' => $filename_prefix
        ));
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        $roles = Role::all()->toArray();

        if ($this->request->isMethod('post')) {
            Input::flash();
            $rules = array(
                'username' => 'required|min:3',
                'email' => 'required|email',
                'password' => 'required|min:3'
            );

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {

                return Redirect::route('userCreate')->withInput()->withErrors($validator);
            }

            $role = Role::find(Input::get('role'));

            $user = new User();
            $user->username = Input::get('username');
            $user->email = Input::get('email');
            $user->password = Input::get('password');
            $user->setLastLogin();
            $user->save();
            $user->roles()->attach($role->id);
            $name = $user->username;
            Session::flash('info', "user <strong>$name</strong> successfully created");

            return Redirect::route('users');
        }

        return View::make('admin.user.create', array('roles' => $roles,));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {
        $user = User::find($id);

        if(is_null($user)) {
            Session::flash('warning', "user with ID $id not found");

            return Redirect::route('users');
        }
        if ($this->request->isMethod('post')) {
            Input::flash();
            $rules = array(
                'username' => 'required|min:3',
                'email' => 'required|email',
            );
            $validator = Validator::make(Input::only('username', 'email'), $rules);
            if ($validator->fails()) {

                return Redirect::route('userEdit', array('id'=> $id))->withInput()->withErrors($validator);
            }

            $password = Input::get('password');

            $user->username = Input::get('username');
            $user->email = Input::get('email');
            ($password == '') ? : $user->password = $password;
            $user->save();
            $name = $user->username;
            Session::flash('info', "user <strong>$name</strong> successfully updated");

            return Redirect::route('users');
        }

        return View::make('admin.user.edit', array('user' => $user));
    }

    public function delete($id) {
        $admin = $this->sentry->getUser()->id;
        $this->logger('user', 'delete', "Admin $admin delete user $id.");

        $user = User::find($id);

        if (is_null($user)) {
            Session::flash('warning', "User with ID $id not found");

            return Redirect::route('users');
        }

        $name = $user->username;
        if ($user->delete($id)) {
            Session::flash('info', "User <strong>$name</strong> successfully deleted.");
            $this->logger('user', 'delete', "Admin $admin successfully deleted user $id.");
        };

        return Redirect::route('users');
    }

    public function searching() {

        $searchText = trim(strip_tags(addslashes(Input::get('search'))));
        return $searchText == '' ? Redirect::route('users') : Redirect::route('search', array('searchText' => $searchText));
    }

    public function search($searchText) {

        $searchText = trim(strip_tags(addslashes($searchText)));
        $users = DB::table('users')
            ->where('username','like', '%'.$searchText.'%')
            ->orWhere('email','like', '%'.$searchText.'%')
            ->orderBy('id')
            ->paginate(AdminBaseController::PAGINATE);

        return View::make('admin.user.users', array(
            'users' => $users,
        ));
    }

    public function updateProfileCache()
    {
        Artisan::call('profile:update-cache', array());

        return Response::json(['status' => 'success', 'data' => 'Profile cache update successful']);
    }
}
