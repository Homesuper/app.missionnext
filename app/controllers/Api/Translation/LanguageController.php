<?php


namespace MissionNext\Controllers\Api\Translation;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Language\LanguageModel;

/**
 * Class LanguageController
 * @package MissionNext\Controllers\Api\Translation
 */
class LanguageController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function getIndex()
    {

        return new RestResponse(LanguageModel::all());
    }

    /**
     * @return RestResponse
     */
    public function postApplication()
    {
        $languages = $this->request->request->get('languages');
        $this->getApp()->languages()->sync($languages ?: []);

        return new RestResponse($this->getApp()->languages);
    }

    /**
     * @return RestResponse
     */
    public function getApplication()
    {

        return new RestResponse($this->getApp()->languages);
    }
} 