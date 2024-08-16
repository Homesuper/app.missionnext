<?php


namespace MissionNext\Controllers\Api\Subscription;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Repos\Subscription\SubConfigRepository;
use MissionNext\Repos\Subscription\SubConfigRepositoryInterface;

/**
 * Class SubConfigController
 * @package MissionNext\Controllers\Api\Subscription
 */
class SubConfigController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function getIndex()
    {
        /** @var  $repo SubConfigRepository */
        $repo = $this->repoContainer[SubConfigRepositoryInterface::KEY];

        return new RestResponse($repo->allConfigs());
    }
} 