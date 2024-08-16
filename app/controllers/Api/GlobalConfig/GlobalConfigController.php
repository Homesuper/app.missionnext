<?php


namespace MissionNext\Controllers\Api\GlobalConfig;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Configs\GlobalConfig;

/**
 * Class GlobalConfigController
 * @package MissionNext\Controllers\Api\GlobalConfig
 */
class GlobalConfigController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function getIndex()
    {

        return new RestResponse(GlobalConfig::all());
    }

} 