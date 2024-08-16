<?php

namespace MissionNext\DB\SqlStatement;


class Sqlite extends Mysql
{
    public function foreignKeyChecksOff()
    {

        return "PRAGMA foreign_keys=OFF";
    }

    public function foreignKeyChecksOn()
    {

        return "PRAGMA foreign_keys=ON";
    }

    public function truncateTable($tableName){

        return "DELETE FROM $tableName; UPDATE SQLITE_SEQUENCE SET seq = 0 WHERE name = $tableName";
    }
} 