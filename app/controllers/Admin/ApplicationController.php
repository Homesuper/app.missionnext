<?php
namespace MissionNext\Controllers\Admin;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MissionNext\Models\Application\Application;
use MissionNext\Models\Subscription\SubConfig;

class ApplicationController extends AdminBaseController {

    /**
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $applications = Application::orderBy('id')->paginate(AdminBaseController::PAGINATE);

        return View::make('admin.application.applications', array(
            'applications' => $applications,
        ));
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if ($this->request->isMethod('post')) {

            Input::flash();
            $rules = array(
                'app_name' => 'required|min:3',
                'public_key' => 'required|min:3',
                'private_key' => 'required|min:3'
            );

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {

                return Redirect::route('applicationCreate')->withInput()->withErrors($validator);
            }

            $newApp = new Application();
            $newApp->name = Input::get('app_name');
            $newApp->public_key = Input::get('public_key');
            $newApp->private_key = Input::get('private_key');
            $newApp->save();

            $configs = SubConfig::defConfig();
            foreach($configs as $config){
                foreach($config['partnership'] as $p ) {
                    SubConfig::updateOrCreate([
                        'app_id' => $newApp->id(),
                        'partnership' => $p['level'],
                        'role' => $config['role']['key']
                    ],[
                        "price_month" => $p['price_month'],
                        "price_year" => $p['price_year'],
                        "partnership_status" => $p["partnership_status"],
                    ]);
                }
            }

            $name = $newApp->name;
            Session::flash('info', "application <strong>$name</strong> successfully created");

            return Redirect::route('applications');
        }

        return View::make('admin.application.create');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

        $application = Application::find($id);

        if(is_null($application)) {
            Session::flash('warning', "application with ID $id not found");

            return Redirect::route('applications');
        }

        if ($this->request->isMethod('post')) {
            Input::flash();
            $rules = array(
                'name' => 'required|min:3',
                'public_key' => 'required|min:3',
            );
            $validator = Validator::make(Input::only('name', 'public_key'), $rules);
            if ($validator->fails()) {

                return Redirect::route('applicationEdit', array('id'=> $id))->withInput()->withErrors($validator);
            }

            $private_key = Input::get('private_key');

            $application->name = Input::get('name');
            $application->public_key = Input::get('public_key');
            ($private_key == '') ? : $application->private_key = $private_key;
            $application->save();
            $name = $application->name;
            Session::flash('info', "application <strong>$name</strong> successfully updated");

            return Redirect::route('applications');
        }

        return View::make('admin.application.edit', array('application' => $application));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function delete($id) {
        if($this->request->isMethod('delete')) {

            $application = Application::find($id);
            $name = $application->name;
            $application->delete();

            Session::flash('info', "application <strong>$name</strong> successfully deleted");

            return Redirect::route('applications');
        } else {

            return Redirect::route('applications');
        }
    }

}