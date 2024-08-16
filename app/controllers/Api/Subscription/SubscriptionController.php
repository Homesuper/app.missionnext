<?php


namespace MissionNext\Controllers\Api\Subscription;


use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Api\Service\Payment\PaymentGatewayInterface;
use MissionNext\Models\Subscription\Partnership;
use MissionNext\Repos\Subscription\SubscriptionRepository;
use MissionNext\Repos\Subscription\SubscriptionRepositoryInterface;
use MissionNext\Repos\Subscription\TransactionRepository;
use MissionNext\Repos\Subscription\TransactionRepositoryInterface;

/**
 * Class SubscriptionController
 * @package MissionNext\Controllers\Api\Subscription
 */
class SubscriptionController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function postIndex()
    {
        $service = $this->paymentGateway;

        $readyData = $service->processRequest($this->request->request->all());
        //$readyData = $this->request->request->all();
       /** authorize call */
        $subscriptions = $readyData['subscriptions'];
        $transactionData = $readyData['transaction'] ?: [];
        /** @var  $repo SubscriptionRepository */
        $repo = $this->repoContainer[SubscriptionRepositoryInterface::KEY];

        /** @var  $transactionRepo TransactionRepository */
        $transactionRepo = $this->repoContainer[TransactionRepositoryInterface::KEY];

        return new RestResponse($transactionRepo
                           ->syncWithSubscriptions($repo->saveMany($subscriptions), $transactionData)
                          );
    }

    /**
     * @return RestResponse
     */
    public function postAdd()
    {
        $service = $this->paymentGateway;
        $readyData = $service->processRequest($this->request->request->all());
        $subscriptions = $readyData['subscriptions'];
        $transactionData = $readyData['transaction'] ?: [];
        /** @var  $repo SubscriptionRepository */
        $repo = $this->repoContainer[SubscriptionRepositoryInterface::KEY];

        /** @var  $transactionRepo TransactionRepository */
        $transactionRepo = $this->repoContainer[TransactionRepositoryInterface::KEY];

        return new RestResponse($transactionRepo
            ->syncWithSubscriptions($repo->addSubscription($subscriptions), $transactionData)
        );
    }


    public function getFor($userId)
    {
        /** @var  $repo SubscriptionRepository */
        $repo = $this->repoContainer[SubscriptionRepositoryInterface::KEY];

        return new RestResponse($repo->userSubscriptions($userId));
    }

} 