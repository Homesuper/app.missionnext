<?php


namespace MissionNext\Models;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Facade\SecurityContext;

abstract class AbstractDynamicModel extends Model implements ModelInterface
{
    protected  static $tableName = null;

    protected  static $tableRolePrefix = null;

    protected  static $tablePrefix = null;

    /**
     * @param $tableRolePrefix
     *
     * @return static
     */
    public static function table($tableRolePrefix)
    {
        static::$tableRolePrefix =  $tableRolePrefix;
        static::$tableName = $tableRolePrefix."_".static::$tablePrefix;

        return new static;
    }

    public static function getTableName()
    {

        return static::$tableName;
    }


    public function __construct(array $attr = [] )
    {
        $this->table = static::$tableRolePrefix ? static::getTableName() : $this->roleTableName();
        parent::__construct($attr);
    }

    public function newQuery()
    {
        static::$tableRolePrefix = null;

        return parent::newQuery();
    }

    /**
     * @return string
     */
    protected function roleTableName()
    {
        return SecurityContext::getInstance()->role()."_".static::$tablePrefix;
    }
} 