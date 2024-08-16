<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class AlterProfileTableChangeValueColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$create =  function ($role)
		{
			DB::statement("ALTER TABLE ".$role."_profile ALTER COLUMN value TYPE varchar(1023) ");
		};

		$create(BaseDataModel::CANDIDATE);
		$create(BaseDataModel::ORGANIZATION);
		$create(BaseDataModel::AGENCY);
		$create(BaseDataModel::JOB);

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
