<?php

namespace MissionNext\Api\Service\Matching\Data\Type;


class StringObject extends AbstractDataType
{
    /**
     * @return bool
     */
    public function isValid()
    {

        return true;
    }

    /**
     * @param $value
     *
     * @return float
     */
    public function transform($value)
    {

        return  $value;
    }
} 