<?php

namespace MissionNext\Api\Service\Matching;


use MissionNext\Models\CacheData\UserCachedData;
use MissionNext\Models\CacheData\UserCachedDataTrans;
use MissionNext\Models\Language\LanguageModel;

class TransData {

    private $result= [];

    public function __construct(LanguageModel $languageModel, $userType, array $result){

        if (!count($result)){

             $this->result = $result;
        }
        $ids = array_fetch($result, 'id');
        if ($ids) {
            $transCache = UserCachedDataTrans::table($userType);
            $data = $transCache->whereIn("id", $ids)->whereLangId($languageModel->id)->get();


            $data->each(function ($el) use (&$result) {
                foreach ($result as &$r) {
                    if ($r['id'] == $el->id) {
                        $r['profileData'] = $el->getData()['profileData'];
                    }
                }
            });
        }

        $this->result = $result;
    }

    public function get()
    {

        return $this->result;
    }

}