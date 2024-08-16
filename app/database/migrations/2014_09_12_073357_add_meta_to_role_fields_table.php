<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaToRoleFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach(\MissionNext\Models\DataModel\BaseDataModel::allRoles() as $role)
        {
            DB::statement("ALTER TABLE {$role}_fields ADD COLUMN meta JSON");
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        foreach(\MissionNext\Models\DataModel\BaseDataModel::allRoles() as $role)
        {
            DB::statement("ALTER TABLE {$role}_fields DROP COLUMN meta");
        }
	}

}
