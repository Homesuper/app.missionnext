<?php
namespace MissionNext\DB\SqlStatement;

class Postgre extends SqlStatement
{

    public function foreignKeyChecksOff()
    {

        return "SET session_replication_role = replica";
    }

    public function foreignKeyChecksOn()
    {


        return "SET session_replication_role = DEFAULT";
    }

    public function truncateTable($tableName)
    {

        return "TRUNCATE $tableName RESTART IDENTITY CASCADE";
    }

    public function groupConcat($fieldName, $alias = null, $orderByField = null)
    {
        $alias = $alias ? " as $alias" : '';
        $orderByField = $orderByField ? $orderByField : $fieldName;

        return "string_agg(($fieldName)::text, '|'  ORDER BY $orderByField) $alias";
    }

//    public function groupConcat($fieldName, $alias = null, $orderByField = null)
//    {
//        $alias = $alias ? " as $alias" : '';
//        $orderByField = $orderByField ? $orderByField : $fieldName;
//
//        return "array_agg(($fieldName)::text) $alias";
//    }

} 