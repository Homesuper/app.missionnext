<?php
namespace MissionNext\Repos;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\ModelInterface;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $modelClassName;
    /**
     * @var Model
     */
    protected $model;

    /** @var  RepositoryContainer */
    protected $repoContainer;


    public function __construct()
    {
        $this->model = new $this->modelClassName;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes)
    {
        $this->model = call_user_func_array("{$this->modelClassName}::create", array($attributes));

        return $this->model;
    }

    /**
     * @param RepositoryContainer $container
     *
     * @return $this
     */
    public function setRepoContainer(RepositoryContainer $container)
    {
        $this->repoContainer = $container;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function all($columns = array('*'))//@TODO Boot current field model
    {
        return call_user_func_array("{$this->modelClassName}::all", array($columns));
    }

    /**
     * @param $id
     * @param array $columns
     *
     * @return Model
     */
    public function find($id, $columns = array('*'))
    {
        $this->model = call_user_func_array("{$this->modelClassName}::find", array($id, $columns));

        return $this->model;
    }

    /**
     * @param $id
     * @param array $columns
     *
     * @return Model
     */
    public function findOrFail($id, $columns = array('*'))
    {
        $this->model = call_user_func_array("{$this->modelClassName}::findOrFail", array($id, $columns));

        return $this->model;
    }

    public function destroy($ids)
    {
        return call_user_func_array("{$this->modelClassName}::destroy", array($ids));
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {

        return call_user_func_array("{$this->modelClassName}::where", array($column, $operator, $value, $boolean));

    }

    /**
     * @param $string
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function with($string)
    {

        return call_user_func_array("{$this->modelClassName}::with", array($string));
    }

    /**
     * @param null $select
     * @return \Doctrine\DBAL\Query\QueryBuilder This QueryBuilder instance.
     */
    public function select($select = null)
    {

        return call_user_func_array("{$this->modelClassName}::select", array($select));
    }

} 