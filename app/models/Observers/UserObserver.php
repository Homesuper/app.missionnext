<?php
namespace MissionNext\Models\Observers;

use MissionNext\Models\ProfileInterface;
use MissionNext\Models\User\User;

class UserObserver extends AbstractUserObserver implements ModelObserverInterface
{

    public function saved(ProfileInterface $model)
    {
        $this->runClosures($model, static::SAVED);
       // $model->getRepo()->updateUserCachedData($model);
        //  var_dump('---1---');
    }

    public function created(ProfileInterface $model)
    {
        $this->runClosures($model, static::CREATED);
        //$model->getRepo()->addUserCachedData($model);
    }

    protected function runClosures(ProfileInterface $model, $event)
    {
        $method = "getOn".ucfirst($event);

        /** @var $model User */
        foreach ($model->$method() as $func) {
            call_user_func($func, $model);
        }
    }

} 