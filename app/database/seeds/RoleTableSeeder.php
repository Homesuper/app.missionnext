<?php
use MissionNext\Models\DataModel\AppDataModel;

class RoleTableSeeder extends BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("roles"));
        DB::table('roles')->insert(array(
            array('name' => 'ROLE_CANDIDATE', 'role' => AppDataModel::CANDIDATE),
            array('name' => 'ROLE_ORGANIZATION', 'role' => AppDataModel::ORGANIZATION),
            array('name' => 'ROLE_AGENCY', 'role' => AppDataModel::AGENCY),
        ));
    }
}