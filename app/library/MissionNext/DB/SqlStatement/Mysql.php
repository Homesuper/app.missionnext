<?php
namespace MissionNext\DB\SqlStatement;

class Mysql extends SqlStatement
{

    public function foreignKeyChecksOff()
    {

        return "SET foreign_key_checks = 0";
    }

    public function foreignKeyChecksOn()
    {

        return "SET foreign_key_checks = 1";
    }

    public function truncateTable($tableName){

        return "TRUNCATE $tableName";
    }

    public function groupConcat($fieldName,  $alias = null)
    {
        $alias = $alias ? " as $alias" : '';

        return "GROUP_CONCAT($fieldName) $alias";
    }

} 