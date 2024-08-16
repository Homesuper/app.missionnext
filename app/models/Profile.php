<?php
namespace MissionNext\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User;

class Profile extends Eloquent
{
    private $model;

    public function setModel(ProfileInterface $model)
    {
        $apps = $model->appData();

        $this->model = $model;
        $this->app_ids = $apps->lists("id");
        $this->app_names = $apps->lists("public_key");
    }

    /**
     * @return User|Job
     */
    public function getModel()
    {

        return $this->model;
    }
} 