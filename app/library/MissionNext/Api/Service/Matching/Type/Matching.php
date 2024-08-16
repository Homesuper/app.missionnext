<?php


namespace MissionNext\Api\Service\Matching\Type;


use MissionNext\Api\Service\Matching\Data\MatchingDataInterface;

abstract class Matching
{
   /** @var $matchingData MatchingDataInterface */
   protected $matchingData;

    /**
     * @param MatchingDataInterface $matchingDataInterface
     *
     * @return $this
     */
   public function setMatchingData(MatchingDataInterface $matchingDataInterface)
   {
        $this->matchingData = $matchingDataInterface;

        return $this;
   }

   abstract public function isMatches();

    /**
     * @return []
     */
   protected function getMainValues()
   {

      return  $this->matchingData->getReverseMatching()
                   ? $this->matchingData->getMatchingValues()
                   :  $this->matchingData->getMainValues();
   }

    /**
     * @return []
     */
    protected function getMatchingValues()
   {

       return  $this->matchingData->getReverseMatching()
                 ? $this->matchingData->getMainValues()
                 :  $this->matchingData->getMatchingValues();
   }
} 