<?php


namespace MissionNext\Models\CacheData;

use MissionNext\Facade\SecurityContext;
use MissionNext\Models\Language\LanguageModel;

class UserCachedDataTrans extends AbstractCachedData
{
    protected  static $tableName = null;
    protected  static $tableRolePrefix = null;

    protected  static $tablePrefix = 'cached_profile_trans';


    public function setLang(LanguageModel $languageModel)
    {
        $this->lang_id = $languageModel->id;

        return $this;
    }
}