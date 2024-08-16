<?php
namespace MissionNext\Controllers\Admin;

use Illuminate\Support\Facades\View;
use MissionNext\Helpers\Language;
use MissionNext\Models\Language\LanguageModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use MissionNext\Repos\Languages\LanguageRepository;
use MissionNext\Repos\Languages\LanguageRepositoryInterface;

class LanguageController extends AdminBaseController {

    /**
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        
        /** @var  $languageRepo LanguageRepository */
        $languageRepo = $this->repoContainer[LanguageRepositoryInterface::KEY];
        $languages = $languageRepo->getModel()->orderBy('id')->paginate(AdminBaseController::PAGINATE);


        return View::make('admin.language.languages', array('langs' => $languages));
    }

    /**
     *
     * @return \Illuminate\View\View
     */
    public function create() {
        if ($this->request->isMethod('post')) {

            Input::flash();
            $rules = array(
                'key' => 'required|unique:languages,key',
            );

            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {

                return Redirect::route('languageCreate')->withInput()->withErrors($validator);
            }

            $language = new LanguageModel();
            $language->key = Input::get('key');
            $language->name = Language::$codes[$language->key];
            $language->save();

            $name = $language->name;
            Session::flash('info', "language <strong>$name</strong> successfully created");

            return Redirect::route('languages');
        }

        return View::make('admin.language.create');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id) {

        /** @var  $languageRepo LanguageRepository */
        $languageRepo = $this->repoContainer[LanguageRepositoryInterface::KEY];
        $language = $languageRepo->find($id);

        if(is_null($language)) {
            Session::flash('warning', "language with ID $id not found");

            return Redirect::route('languages');
        }
        if ($this->request->isMethod('post')) {
            Input::flash();
            $rules = array(
                'key' => 'required|unique:languages,key,'.$id,
            );
            $validator = Validator::make(Input::all(), $rules);
            if ($validator->fails()) {

                return Redirect::route('languageEdit', array('id'=> $id))->withInput()->withErrors($validator);
            }

            $language->key = Input::get('key');
            $language->name = Language::$codes[Input::get('key')];
            $language->save();
            $name = $language->name;
            Session::flash('info', "language <strong>$name</strong> successfully updated");

            return Redirect::route('languages');
        }

        return View::make('admin.language.edit', array('language' => $language));
    }

    public function delete($id) {
        if($this->request->isMethod('delete')) {

            $languageRepo = $this->repoContainer[LanguageRepositoryInterface::KEY];
            $language = $languageRepo->find($id);
            $name = $language->name;
            $language->delete();
            Session::flash('info', "language <strong>$name</strong> successfully deleted");

            return Redirect::route('languages');
        } else {

            return Redirect::route('languages');
        }
    }
}
