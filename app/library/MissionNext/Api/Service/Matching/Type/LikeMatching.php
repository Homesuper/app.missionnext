<?php

namespace MissionNext\Api\Service\Matching\Type;



class LikeMatching extends Matching
{
    /**
     * @return bool
     */
    public function isMatches()
    {
        $mainValues = $this->getMainValues();
        $matchingValues = $this->getMatchingValues();

        foreach ($mainValues as $mainValue) {
            foreach ($matchingValues as $matchingValue) {
                if ( str_is("*".$mainValue."*", $matchingValue) || str_is("*".$matchingValue."*", $mainValue)  ) {

                    return true;
                }
            }
        }
        return false;
    }
} 