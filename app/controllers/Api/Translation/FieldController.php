<?php


namespace MissionNext\Controllers\Api\Translation;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Dictionary\BaseDictionary;
use MissionNext\Models\Field\BaseField;
use MissionNext\Models\Field\Candidate;
use MissionNext\Models\Field\IField;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Repos\Field\FieldRepository;
use MissionNext\Repos\Field\FieldRepositoryInterface;
use MissionNext\Repos\Translation\FieldRepository as TransFieldRepo;
use MissionNext\Repos\Translation\FieldRepositoryInterface as TransFieldRepoInterface;

/**
 * Class FieldController
 * @package MissionNext\Controllers\Api\Translation
 */
class FieldController extends BaseController
{

    public function getIndex($type)
    {
       /** @var  $fieldRepo FieldRepository */
       $fieldRepo = $this->repoContainer[FieldRepositoryInterface::KEY];

       $appLangIds = $this->getApp()->languages->lists('id');
       array_unshift($appLangIds, 0);

       $transFields = [];
       foreach($appLangIds as $langId){
           array_push($transFields, [
                      'lang_id' => $langId,
                      'fields' => $fieldRepo->fieldsExpandedTrans(LanguageModel::find($langId) ?: new LanguageModel())->get()->toArray(),
                      ] );
       }

       return new RestResponse($transFields);
    }

    /**
     * @param $type
     *
     * @return RestResponse
     */
    public function postIndex($type)
    {
        $translations = $this->request->request->get('languages');
        /** @var  $fieldRepo FieldRepository */
        $fieldRepo = $this->repoContainer[FieldRepositoryInterface::KEY];
        /** @var  $fieldModel IField */
        foreach($translations as $trans){
            $fieldModel = $fieldRepo->getModel()->findOrFail($trans["field_id"]);
            $langId = $trans["lang_id"];

            if ($trans['choices'] && is_array($trans['choices']) && $choices = $trans['choices']){
                $defChoicesIds = $fieldModel->choices()->get()->lists('id');
                $transChoicesIds = array_keys($choices);

                foreach($defChoicesIds as $defId){
                    /** @var  $choice BaseDictionary */
                   $choice =  $fieldModel->choices()->getRelated()->findOrFail($defId);
                   $choice->languages()->detach([$langId]);

                    if (in_array($defId, $transChoicesIds)){

                        $choice->languages()->attach([$langId => [ 'value' => $choices[$defId]] ]);
                    }


                }
            }
            $fieldModel->languages()->detach([ $langId ]);
            $fieldModel->languages()->attach([ $langId => [ 'name' => $trans['label'], 'note' => $trans['note'] ] ]);
        }


        return new RestResponse($fieldModel->languages);
    }

} 