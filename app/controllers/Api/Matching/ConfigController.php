<?php

namespace MissionNext\Controllers\Api\Matching;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Queue;
use MissionNext\Api\Response\RestResponse;
use MissionNext\Api\Service\Matching\Queue\Master\ConfigUpdateMatching;
use MissionNext\Controllers\Api\BaseController;

/**
 * Class ConfigController
 * @package MissionNext\Controllers\Api\Matching
 */
class ConfigController extends BaseController
{
    /**
     * @param $type
     *
     * @return RestResponse
     */
    public function getIndex($type)
    {

        return new RestResponse($this->matchingConfigRepo()->where("app_id", '=', $this->securityContext()->getApp()->id)->with("mainField")->with("matchingField")->get());
    }

    /**
     * @param $type
     *
     * @return RestResponse
     */
    public function putIndex($type)
    {
        $model = $this->matchingConfigRepo()->getModel();
        $modelAppQuery = $model->where('app_id', '=', $this->getApp()->id());
        $modelAppQuery->delete();
        $configs = Input::get("configs");

        if (is_null($configs)){

            return new RestResponse([]);
        }
        $this->matchingConfigRepo()->insert($configs);

        $queueData = ["appId"=>$this->getApp()->id(), "role" => $this->securityContext()->role(), "userId" => 0];
        ConfigUpdateMatching::run($queueData);

        return new RestResponse( $modelAppQuery->with("mainField")->with('matchingField')->get() );
    }
} 