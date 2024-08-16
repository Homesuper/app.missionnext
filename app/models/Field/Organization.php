<?php


namespace MissionNext\Models\Field;

use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\User\User as UserModel;
use MissionNext\Models\Dictionary\Organization as OrganizationDictionary;
use MissionNext\Models\DataModel\AppDataModel;

class Organization extends BaseField implements ModelInterface, IRoleField
{

    protected $table = 'organization_fields';

    protected $roleType = BaseDataModel::ORGANIZATION;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {

        return $this->belongsToMany(UserModel::class, 'organization_profile', 'field_id', 'user_id')->withPivot('value');
    } //@TODO first fields_id because Candiate is Field entity

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'organization_fields_trans', 'field_id', 'lang_id')->withPivot('name', 'note');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function choices()
    {

        return $this->hasMany(OrganizationDictionary::class, 'field_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dataModels()
    {

        return $this->belongsToMany(AppDataModel::class, 'data_model_organization_fields', 'field_id', 'data_model_id');
    }


} 