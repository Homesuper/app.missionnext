<?php


namespace MissionNext\Controllers\Admin;


use Carbon\Carbon;
use MissionNext\Models\Admin\AdminUserModel;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Repos\Subscription\SubscriptionRepository;
use MissionNext\Validators\Administrator as AdminValidator;

class Administrator extends AdminBaseController
{
    const VIEW_PREFIX = 'admin.administrator';

    const ROUTE_PREFIX = 'administrator';
    /**
     *
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $users = $this->sentry->findAllUsers();
//        $users = array_filter($users, function($user){
//
//            return $this->sentry->getUser()->id != $user->id;
//        });
        return $this->view->make($this->viewTemplate('index'), ['models' => $users ]);

    }

    /**
     * @return \Illuminate\View\View
     */
    public function getCreate()
    {

        return $this->view->make($this->viewTemplate('create'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postIndex()
    {

        $validator = new AdminValidator( $this->request );


        if (!$validator->passes())
        {

            return $this->redirect->route($this->routeName('create'))->withInput()->withErrors($validator->validator());
        }

        $adminData =  [
          'username' => $this->request->request->get('username'),
          'email' => $this->request->request->get('email'),
          'password' => $this->request->request->get('password'),
          'activated' => true
        ];

        $this->sentry->createUser($adminData);

        $this->session->flash('info', "Admin successfully cloned");

        return $this->redirect->route($this->routeName('list'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function getEdit($id)
    {
        $model = $this->sentry->findUserById($id);

        $this->session->flash('model', $model);

        return $this->view->make($this->viewTemplate('edit'), ['model' => $model] );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($id)
    {
        $validator = (new AdminValidator( $this->request ))
            ->updateRuleUnique($id, 'username')
            ->updateRuleUnique($id, 'email');

        $model = $this->session->get('model');


        $messageBag = $validator->validator()->errors();

//        if (!$model->checkPassword($this->request->request->get('old_password'))){
//
//            $messageBag = $validator->validator()->errors()->add('old_password', 'Wrong old password');
//
//        }

        if (!$validator->passes() || $messageBag->count() )
        {
            $validator->validator()->errors()->merge($messageBag);

            return $this->redirect->route($this->routeName('edit'), [$id])->withInput()->withErrors($validator->validator());
        }
        $this->request->request->remove('_token');
        $model->username = $this->request->request->get('username');
        $model->email = $this->request->request->get('email');
        $model->password = $this->request->request->get('new_password');

        $model->save();

        $this->session->flash('info', "Admin successfully updated");

        return $this->redirect->route($this->routeName('list'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteIndex($id)
    {
        $model = $this->sentry->findUserById($id);

        $model->remove();
        $this->session->flash('info', "Admin successfully deleted");

        return $this->redirect->route($this->routeName('list'));
    }
} 