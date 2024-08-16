<?php


namespace MissionNext\Controllers\Api\Translation;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Translation\CustomTrans;

/**
 * Class CustomTransController
 * @package MissionNext\Controllers\Api\Translation
 */
class CustomTransController extends BaseController
{

    public function postIndex()
    {
        $data = $this->request->request->all();
        foreach($data['custom'] as $transGroup)
        {
            $dataTrans = new \ArrayObject($transGroup, \ArrayObject::ARRAY_AS_PROPS);
            $dataTrans->offsetSet('app_id', $this->getApp()->id());
            $dataTrans->lang_id = !$dataTrans->lang_id ? null : $dataTrans->lang_id;
            /** @var  $customTrans CustomTrans */
            $customTrans =  CustomTrans::whereLangId($dataTrans->lang_id)
                                        ->whereAppId($dataTrans->app_id)
                                        ->whereKey($dataTrans->key)
                                        ->first() ? : new CustomTrans();


            $customTrans->app_id  ?   $customTrans->updateTransData($dataTrans)
                                 :   $customTrans->insertTransData($dataTrans);


        }

        return new RestResponse([$data]);
    }

    /**
     * @return RestResponse
     */
    public function getIndex()
    {

         return  new RestResponse( (new CustomTrans())->whereAppId($this->getApp()->id())->get() );
    }
} 