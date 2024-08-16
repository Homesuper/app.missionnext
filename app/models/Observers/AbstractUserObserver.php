<?php

namespace MissionNext\Models\Observers;

use MissionNext\Models\ProfileInterface;


abstract class AbstractUserObserver
{

    abstract public function saved(ProfileInterface $model);

    abstract public function created(ProfileInterface $model);

}