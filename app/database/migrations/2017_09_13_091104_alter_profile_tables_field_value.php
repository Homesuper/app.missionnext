<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;
use Illuminate\Support\Facades\DB;

class AlterProfileTablesFieldValue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach(BaseDataModel::userRoles() as $role) {
            DB::statement("ALTER TABLE {$role}_profile ALTER COLUMN value SET DATA TYPE text");
        }
        DB::statement("ALTER TABLE job_profile ALTER COLUMN value SET DATA TYPE text");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        foreach(BaseDataModel::userRoles() as $role) {
            DB::statement("ALTER TABLE {$role}_profile ALTER COLUMN value SET DATA TYPE varchar(1023)");
        }
        DB::statement("ALTER TABLE job_profile ALTER COLUMN value SET DATA TYPE varchar(1023)");
	}

}
