<?php


namespace MissionNext\Models\Observers;


use MissionNext\Models\Configs\GlobalConfig;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\User;

class SubscriptionObserver implements ModelObserverInterface
{
//    public function created(Subscription $model)
//    {
//        /** @var  $user User */
//        $user = User::find( $model->user_id );
//        $user->appsStatuses()->detach( $model->app_id );
//        $user->appsStatuses()->attach( $model->app_id, ['is_active' => true] );
//    }

    public function updating(Subscription $model)
    {
        $absDaysLeft = abs($model->days_left);

        if ($model->days_left < 0 ){
            $gracePeriod = (new GlobalConfig())->gracePeriod();
            if ($absDaysLeft < $gracePeriod) {
                $model->status = Subscription::STATUS_GRACE;
            } elseif ($absDaysLeft > $gracePeriod) {
                $model->status = Subscription::STATUS_EXPIRED;
            }
        }else{
            $model->status = Subscription::STATUS_ACTIVE;
        }

    }

} 