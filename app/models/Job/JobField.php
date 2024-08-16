<?php

namespace MissionNext\Models\Job;


use MissionNext\Models\DataModel\AppDataModel;
use MissionNext\Models\Field\BaseField;
use MissionNext\Models\Job\Job as JobModel;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ModelInterface;

class JobField extends BaseField implements IJobField, ModelInterface
{

    protected $table = 'job_fields';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobs()
    {

        return $this->belongsToMany(JobModel::class, 'job_profile', 'field_id', 'job_id')->withPivot('value');
    } //@TODO first fields_id because Candiate is Field entity

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function choices()
    {

        return $this->hasMany(JobDictionary::class, 'field_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'job_fields_trans', 'field_id', 'lang_id')->withPivot('name', 'note');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dataModels()
    {

        return $this->belongsToMany(AppDataModel::class, 'data_model_job_fields', 'field_id', 'data_model_id');
    }
} 