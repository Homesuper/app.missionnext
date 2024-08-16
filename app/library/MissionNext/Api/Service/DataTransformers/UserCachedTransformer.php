<?php


namespace MissionNext\Api\Service\DataTransformers;


use MissionNext\Repos\DataTransformer;

class UserCachedTransformer extends DataTransformer
{
    /**
     * @param $limit
     *
     * @return $this
     */
    private function take($limit)
    {
        $this->builder = call_user_func_array(array($this->getQueryBuilder(), 'take'), [$limit]);

        return $this;
    }

    /**
     * @param $offset
     *
     * @return $this
     */
    private  function skip($offset)
    {
        $this->builder = call_user_func_array(array($this->getQueryBuilder(), 'skip'), [$offset]);

        return $this;
    }

    /**
     * @param $limit
     * @param $offset
     *
     * @return $this
     */
    public function takeAndSkip($limit, $offset)
    {
       return
           $this->take($limit)
                ->skip($offset);
    }
} 