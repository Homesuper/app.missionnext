<?php


namespace MissionNext\Models\Observers;


use MissionNext\Models\Configs\GlobalConfig;
use MissionNext\Models\Subscription\GlobalSubscription;
use MissionNext\Models\Subscription\Subscription;

class GlobalSubscriptionObserver implements ModelObserverInterface
{

    public function updating(GlobalSubscription $model)
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