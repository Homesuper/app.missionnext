<?php


namespace MissionNext\Controllers\Admin\Subscription\Ajax;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Response;
use MissionNext\Controllers\Admin\AdminBaseController;
use MissionNext\Models\Observers\SubscriptionObserver;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\User;
use MissionNext\Repos\Subscription\SubscriptionRepository;
use MissionNext\Repos\Subscription\SubscriptionRepositoryInterface;

class SubscriptionController extends AdminBaseController
{
    /**
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIndex($userId)
    {
        /** @var  $repo SubscriptionRepository */
        $repo = $this->repoContainer[SubscriptionRepositoryInterface::KEY];

        return Response::json($repo->userSubscriptions($userId));
    }

    /**
     * @param $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactions($userId)
    {

        return Response::json((new User)->transactions($userId));
    }

    /**
     * @param $subId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateIndex($subId)
    {
        $update = [];
        $forceClose = false;
        foreach($this->request->request->all() as $property)
        {
            $update[$property['field']] = $property['value'];
            $forceClose = isset($property['forceClose']) ? $property['forceClose'] : false;
        }
        if (!$forceClose){

            Subscription::observe(new SubscriptionObserver());
        }

        /** @var  $subscription Collection */
        $subscription = Subscription::whereId($subId)->get();
        $initSub = $subscription->first();
        $authorizeCode = null;
        if ($initSub->is_recurrent && $initSub->authorize_id && $forceClose){
                $subscription = Subscription::where('authorize_id','=', $initSub->authorize_id)
                            ->where('status', '<>', Subscription::STATUS_CLOSED)
                            ->get();
                $response = $this->paymentGateway->getRecurringBilling()->cancelSubscription($initSub->authorize_id);
                //$authorizeCode = strip_tags($response->xpath('messages/message')[0]->code->asXML());
                $authorizeCode = $response->getMessageCode();
                //code -  E00003, I00001- successful,  I00002 - has already been cancelled
                $subscription->each(function($sub) use ($update){
                    $sub->update($update);
                });
        }else{

            $initSub->update($update);
        }

        /** @var  $repo SubscriptionRepository */
        $repo = $this->repoContainer[SubscriptionRepositoryInterface::KEY];

        return Response::json(['update' => $update, 'subscription' => $repo->find($subId), 'authorizeCode' => $authorizeCode ]);
    }
} 