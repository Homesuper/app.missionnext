<?php


namespace MissionNext\Models\Field;

use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\User\User as UserModel;
use MissionNext\Models\Dictionary\Agency as AgencyDictionary;
use MissionNext\Models\DataModel\AppDataModel;

class Agency extends BaseField implements ModelInterface, IRoleField
{

    protected $table = 'agency_fields';

    protected $roleType = BaseDataModel::AGENCY;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {

        return $this->belongsToMany(UserModel::class, 'agency_profile', 'field_id', 'user_id')->withPivot('value');
    } //@TODO first fields_id because Candiate is Field entity

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'agency_fields_trans', 'field_id', 'lang_id')->withPivot('name', 'note');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function choices()
    {

        return $this->hasMany(AgencyDictionary::class, 'field_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dataModels()
    {

        return $this->belongsToMany(AppDataModel::class, 'data_model_agency_fields', 'field_id', 'data_model_id');
    }


} 