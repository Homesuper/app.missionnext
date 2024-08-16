<?php


namespace MissionNext\Api\Service\Matching\Data\Type;



class Numeric extends AbstractDataType
{
    /**
     * @return bool
     */
    public function isValid()
    {
        foreach($this->matchingData->getMainValues() as $value){
            if (!filter_var($value, FILTER_VALIDATE_FLOAT)){
                return false;
            }
        }

        foreach($this->matchingData->getMatchingValues() as $value){
            if (!filter_var($value, FILTER_VALIDATE_FLOAT)){
                return false;
            }
        }

        return true;
    }

    /**
     * @param $value
     *
     * @return float
     */
    public function transform($value)
    {

        return floatval($value);
    }
} 