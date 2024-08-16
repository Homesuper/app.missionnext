<?php
namespace MissionNext\DB\SqlStatement;

use Illuminate\Support\Facades\DB;
use MissionNext\DB\SqlStatement\RepositoryInterface\IViewFieldRepository;
use MissionNext\Repos\AbstractRepository;

class Sql {

    const MYSQL = "mysql",
          PostgreSQL = "pgsql",
          SQLITE  = "sqlite";
    /**
     * @var SqlStatement
     */
    static protected $dbStatement;

    /**
     * @param AbstractRepository $repo
     *
     * @return SqlStatement|IViewFieldRepository
     */
    public static  function  getDbStatement(AbstractRepository $repo = null)
    {
//        if (static::$dbStatement instanceof SqlStatement){
//
//            return static::$dbStatement;
//        }//@TODO when getting statement more than one times during request

        $baseName = __NAMESPACE__.'\\'.class_basename($repo);

        $class = $baseName."Mysql";


        switch (DB::getDefaultConnection()) {
            case self::MYSQL:
                $class = $baseName."Mysql";
                static::$dbStatement = new $class;
                break;
            case self::PostgreSQL:
                $class = $baseName."Postgre";
                static::$dbStatement = new $class;
                break;
            case self::SQLITE:
                $class = $baseName."Sqlite";
                static::$dbStatement = new $class;
            default:
                static::$dbStatement = new $class;
        }

        return static::$dbStatement;
    }

}