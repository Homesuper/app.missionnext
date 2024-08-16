<?php

namespace MissionNext\Models\Matching;

use MissionNext\Models\Application\Application as AppModel;
use MissionNext\Models\Field\Candidate as CandidateFieldModel;

class Config extends BaseConfig
{

    public $timestamps = false;

    const MATCHING_EQUAL = 1,
          MATCHING_GREATER_OR_EQUAL = 2,
          MATCHING_GREATER = 3,
          MATCHING_LESS_OR_EQUAL = 4,
          MATCHING_LESS = 5,
          MATCHING_LIKE = 6;



    protected $fillable = array('weight', 'matching_type');



    /**
     * @param $matchingType
     *
     * @return $this
     */
    public function setMatchingType($matchingType)
    {
        $this->matching_type = $matchingType;

        return $this;
    }

    /**
     * @param $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function matchingField()
    {

        return $this->belongsTo($this->matchingFieldModelName(), $this->role.'_field_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mainField()
    {

        return $this->belongsTo(CandidateFieldModel::class, 'main_field_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application()
    {

        return $this->belongsTo(AppModel::class, 'app_id', 'id');
    }


} 