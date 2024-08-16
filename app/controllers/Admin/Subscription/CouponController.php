<?php

namespace MissionNext\Controllers\Admin\Subscription;


use MissionNext\Controllers\Admin\AdminBaseController;
use MissionNext\Models\Coupon\Coupon;
use MissionNext\Validators\Coupon as CouponValidator;

class CouponController extends AdminBaseController
{
    const VIEW_PREFIX = 'admin.subscription.coupon';

    const ROUTE_PREFIX = 'sub.coupon';

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        $models = Coupon::orderBy('id')->paginate(static::PAGINATE);

        return $this->view->make($this->viewTemplate('index'), ['models' => $models ]);
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

        $validator = new CouponValidator( $this->request );

        if (!$validator->passes())
        {

            return $this->redirect->route($this->routeName('create'))->withInput()->withErrors($validator->validator());
        }

        Coupon::create($this->request->except('_token'));

        $this->session->flash('info', "Coupon successfully created");

        return $this->redirect->route($this->routeName('list'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function getEdit($id)
    {
        $model = Coupon::findOrFail($id);

        $this->session->flash('model', $model);

        return $this->view->make($this->viewTemplate('edit'),['model' => $model] );
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($id)
    {
        $validator = (new CouponValidator( $this->request ))->updateRuleUnique($id, 'code');

        if (!$validator->passes())
        {

            return $this->redirect->route($this->routeName('edit'), [$id])->withInput()->withErrors($validator->validator());
        }
        /** @var  $model Coupon */
        $model = $this->session->get('model');
        $model->update($this->request->except('_token'));
        $this->session->flash('info', "Coupon successfully updated");

        return $this->redirect->route($this->routeName('list'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteIndex($id)
    {
        $model = Coupon::findOrFail($id);
        $model->delete();
        $this->session->flash('info', "Coupon successfully deleted");

        return $this->redirect->route($this->routeName('list'));
    }
} 