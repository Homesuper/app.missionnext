<?php


namespace MissionNext\Api\Service\Matching\Type;



class GreaterMatching extends Matching
{
    /**
     * @return bool
     */
    public function isMatches()
    {
        $mainValues = $this->getMainValues();
        $matchingValues = $this->getMatchingValues();

        $dataType = $this->matchingData->getDataType();

        foreach ($mainValues as $mainValue) {
            foreach ($matchingValues as $matchingValue) {
                if ( $dataType->transform($mainValue) > $dataType->transform($matchingValue) ) {
                    return true;
                }
            }
        }
        return false;
    }
} 