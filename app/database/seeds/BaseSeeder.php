<?php

class BaseSeeder extends Seeder {

    /**
     * @return \MissionNext\DB\SqlStatement\SqlStatement
     */
    protected  function  getDbStatement()
    {
        return \MissionNext\DB\SqlStatement\Sql::getDbStatement();
    }

//    /**
//     * @return \Illuminate\Foundation\Application
//     */
//    protected  function  createApp()
//    {
//        return require __DIR__ . '/../../../bootstrap/start.php';
//    }

}