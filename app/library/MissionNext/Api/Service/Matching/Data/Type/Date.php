<?php

namespace MissionNext\Api\Service\Matching\Data\Type;

use DateTime;

class Date extends AbstractDataType
{
    /**
     * @return bool
     */
    public function isValid()
    {
        foreach($this->matchingData->getMainValues() as $value){
            if (!filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ["regexp"=>"/\\d{4}-\\d{2}-\\d{2}/"]])){
                return false;
            }
        }

        foreach($this->matchingData->getMatchingValues() as $value){
            if (!filter_var($value, FILTER_VALIDATE_REGEXP, ['options' => ["regexp"=>"/\\d{4}-\\d{2}-\\d{2}/"]])){
                return false;
            }
        }

        return true;
    }

    /**
     * @param $value
     *
     * @return DateTime
     */
    public function transform($value)
    {

        return new DateTime($value);
    }
} 