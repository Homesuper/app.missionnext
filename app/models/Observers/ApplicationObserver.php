<?php

namespace MissionNext\Models\Observers;


use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\AppDataModel;

class ApplicationObserver implements ModelObserverInterface
{

    public function created(Application $model)
    {
        $model->dataModels()->save(AppDataModel::createCandidate());
        $model->dataModels()->save(AppDataModel::createOrganization());
        $model->dataModels()->save(AppDataModel::createAgency());
        $model->dataModels()->save(AppDataModel::createJob());
    }
} 