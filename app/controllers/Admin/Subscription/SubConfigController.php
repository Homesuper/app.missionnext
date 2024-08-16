<?php

namespace MissionNext\Controllers\Admin\Subscription;


use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use MissionNext\Controllers\Admin\AdminBaseController;
use MissionNext\Models\Application\Application;
use MissionNext\Models\Subscription\SubConfig;
use MissionNext\Repos\Subscription\SubConfigRepository;
use MissionNext\Repos\Subscription\SubConfigRepositoryInterface;
use MissionNext\Validators\SubConfig as ConfValidator;

class SubConfigController extends AdminBaseController
{
    const VIEW_PREFIX = 'admin.subscription.config';

    const ROUTE_PREFIX = 'sub.config';

    public function postIndex()
    {
        /** @var  $repo SubConfigRepository */
        $repo = $this->repoContainer[SubConfigRepositoryInterface::KEY];

        $validator = new ConfValidator( $this->request, $repo->getModel()  );

        if (!$validator->passes())
        {

            return $this->redirect->route($this->routeName('create'))->withInput()->withErrors($validator->validator());
        }

        $repo->getModel()->save();

        $this->session->flash('info', "Config successfully created");

        return $this->redirect->route($this->routeName('list'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getIndex()
    {
        /** @var  $repo SubConfigRepository */
        $repo = $this->repoContainer[SubConfigRepositoryInterface::KEY];
        /** @var  $models Paginator */
        $models = $repo->getModel()->orderBy('id')->paginate(static::PAGINATE);

        return $this->view->make($this->viewTemplate('index'), ['models' => $models ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getManagement()
    {
        $application = Application::findOrFail($this->request->query->get('app'));

        return $this->view->make($this->viewTemplate('management'), ['application' => $application ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getCreate()
    {

        return $this->view->make($this->viewTemplate('create'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function getEdit($id)
    {
        /** @var  $repo SubConfigRepository */
        $repo = $this->repoContainer[SubConfigRepositoryInterface::KEY];
        $model = $repo->getModel()->findOrFail($id);
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
        /** @var  $model SubConfig */
        $model = $this->session->get('model');

        $validator = new ConfValidator( $this->request, $model );

        if (!$validator->passes())
        {

            return $this->redirect->route($this->routeName('edit'), [$id])->withInput()->withErrors($validator->validator());
        }
        $model->save();
        $this->session->flash('info', "Config successfully updated");

        return $this->redirect->route($this->routeName('list'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function deleteIndex($id)
    {
        /** @var  $repo SubConfigRepository */
        $repo = $this->repoContainer[SubConfigRepositoryInterface::KEY];
        $model = $repo->getModel()->findOrFail($id);
        $model->delete();
        $this->session->flash('info', "Config successfully deleted");

        return $this->redirect->route($this->routeName('list'));
    }

} 