<?php

namespace MissionNext\Api\Service\Matching\Data\Type;


use MissionNext\Api\Service\Matching\Data\MatchingDataInterface;

abstract class AbstractDataType
{
   protected $matchingData;

   public function __construct(MatchingDataInterface $matchingData)
   {
       $this->matchingData = $matchingData;
   }

   abstract  public function isValid();

   abstract  public function transform($value);
} 