<?php


namespace MissionNext\Api\Service\Matching\Type;



class EqualMatching extends Matching
{
    /**
     * @return bool
     */
    public  function isMatches()
    {
        $mainValues = $this->getMainValues();
        $matchingValues = $this->getMatchingValues();

        foreach($mainValues  as $mainValue){
            if (!empty($matchingValues) && in_array($mainValue, $matchingValues)){
                return true;
            }
        }

        return false;
    }
} 