<?php

namespace MissionNext\Models\Dictionary;

use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\ModelInterface;
use MissionNext\Models\Field\Agency as AgencyField;

class Agency extends BaseDictionary implements ModelInterface {

    protected $table = 'agency_dictionary';

    public function field()
    {
        return $this->belongsTo(AgencyField::class, 'field_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {

        return $this->belongsToMany(LanguageModel::class, 'agency_dictionary_trans', 'dictionary_id', 'lang_id')->withPivot('value');
    }
}