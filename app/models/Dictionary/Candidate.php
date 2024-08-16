<?php
namespace MissionNext\Models\Dictionary;

use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\Field\Candidate as CandidateField;

class Candidate extends BaseDictionary implements ModelInterface {

    protected $table = 'candidate_dictionary';

    public function field()
    {
        return $this->belongsTo(CandidateField::class, 'field_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'candidate_dictionary_trans', 'dictionary_id', 'lang_id')->withPivot('value');
    }
}