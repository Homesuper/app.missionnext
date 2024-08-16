<?php

namespace MissionNext\Models\Observers;


use MissionNext\Facade\SecurityContext;
use MissionNext\Models\FolderApps\FolderApps;

class FolderObserver implements ModelObserverInterface
{

    public function deleted($model)
    {

        FolderApps::where("folder","=",$model->title)
                   ->where("user_type","=", $model->role)
                   ->where("app_id", "=", SecurityContext::getInstance()->getApp()->id())
                   ->delete();
    }

    public function updated($model)
    {

        FolderApps::where("folder","=",$model->getOriginal()['title'])
                    ->where("user_type","=", $model->role)
                    ->where("app_id", "=", SecurityContext::getInstance()->getApp()->id())
                    ->update(["folder" => $model->title]);
    }
} 