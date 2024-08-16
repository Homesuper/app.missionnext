<?php


namespace MissionNext\Models\Job;


use MissionNext\Models\Dictionary\BaseDictionary;
use MissionNext\Models\Language\LanguageModel;

class JobDictionary extends BaseDictionary
{
    protected $table = 'job_dictionary';

    public function field()
    {
        return $this->belongsTo(JobField::class, 'field_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'job_dictionary_trans', 'dictionary_id', 'lang_id')->withPivot('value');
    }
} 