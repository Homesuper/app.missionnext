<?php


namespace MissionNext\Controllers\Api\AppConfig;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Configs\AppConfigs;
use MissionNext\Models\User\User;

/**
 * Class ConfigController
 *
 * @package MissionNext\Controllers\Api\AppConfig
 */
class ConfigController extends BaseController
{
    /**
     * Create App Config
     *
     * @return RestResponse
     */
    public function postIndex()
    {
        $key = $this->request->request->get('key');
        $value = $this->request->request->get('value');

        $value = str_replace("\\", "&#92;", $value);

        $attributes = ['app_id' => $this->getApp()->id(), 'key' => $key];
        $values = array_merge($attributes, ['value' => $value]);
        AppConfigs::updateOrCreate( $attributes, $values );

        return new RestResponse(true);
    }

    /**
     * Get All configs
     *
     * @return RestResponse
     */
    public function getIndex()
    {

        return new RestResponse($this->getApp()->configs);
    }

    /**
     * Get App Config by Key
     *
     * @param string $key
     *
     * @return RestResponse
     */
    public function getKey($key)
    {

        return new RestResponse($this->getApp()->configs()->whereKey($key)->get());
    }



} 