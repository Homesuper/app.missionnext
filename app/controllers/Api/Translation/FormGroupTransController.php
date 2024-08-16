<?php


namespace MissionNext\Controllers\Api\Translation;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Form\AppForm;
use MissionNext\Models\Translation\FormGroupTrans;

/**
 * Class FormGroupTransController
 * @package MissionNext\Controllers\Api\Translation
 */
class FormGroupTransController extends  BaseController
{
    /**
     * @return RestResponse
     */
    public function postIndex()
    {
        $data = $this->request->request->all();
        if (isset($data['groups']) && !empty($data['groups'])) {
            foreach($data['groups'] as $transGroup)
            {
                $dataTrans = new \ArrayObject($transGroup, \ArrayObject::ARRAY_AS_PROPS);
                $dataTrans->offsetSet('app_id', $this->getApp()->id());
                /** @var  $formGroupTrans FormGroupTrans */
                $formGroupTrans =  FormGroupTrans::whereLangId($dataTrans->lang_id)
                    ->whereAppId($dataTrans->app_id)
                    ->whereGroupId($dataTrans->group_id)
                    ->first() ? : new FormGroupTrans();

                $formGroupTrans->app_id  ?   $formGroupTrans->updateTransData($dataTrans)
                    :   $formGroupTrans->insertTransData($dataTrans);
            }
        }

        return new RestResponse([$data]);
    }

    /**
     * @param $type
     * @param $formName
     *
     * @return RestResponse
     */
    public function getGroupTrans($type, $formName)
    {
        /** @var  $form AppForm */
        $form = $this->getApp()->DM()->forms()->whereSymbolKey($formName)->firstOrFail();

        $ids = $form->groups()->get()->lists('id') ? : [0];

        $groupTrans =  FormGroupTrans::whereIn('group_id', $ids)->whereAppId($this->getApp()->id())->get();

        return new RestResponse($groupTrans);
    }
} 