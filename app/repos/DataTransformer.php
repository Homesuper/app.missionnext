<?php
namespace MissionNext\Repos;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Class DataTransformer
 * @package MissionNext\Repos
 */
abstract class DataTransformer
{
    /** @var  QueryBuilder */
    protected  $builder;
    /** @var  TransformDataStrategy */
    private $dataStrategy;

    protected  $totalCount;

    /**
     * @param QueryBuilder $builder
     * @param TransformDataStrategy $dataStrategy
     */
    public function __construct(QueryBuilder $builder, TransformDataStrategy $dataStrategy)
    {
        $this->builder = $builder;
        //$this->totalCount = $builder->count();
        $this->dataStrategy = $dataStrategy;
    }

    public function get($columns = array('*'))
    {
        return $this->dataStrategy->get($this, $columns = array('*'));
    }

    /**
     * @param $pages
     * @return mixed
     */
    public function paginate($pages)
    {

        return $this->dataStrategy->paginate($this, $pages);
    }


    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {

        return $this->builder;
    }

} 