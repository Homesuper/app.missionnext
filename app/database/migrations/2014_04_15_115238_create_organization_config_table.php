<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateOrganizationConfigTable extends Migration {


    private function createTable($field)
    {
        Schema::create('matching_'.$field.'_config', function(Blueprint $table) use ($field)
        {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
            $table->unsignedInteger('main_field_id');
            $table->foreign("main_field_id")->references('id')->on('candidate_fields')->onDelete('cascade');
            $table->unsignedInteger($field.'_field_id');
            $table->foreign($field."_field_id")->references('id')->on($field.'_fields')->onDelete('cascade');
            $table->tinyInteger("weight")->default(0);
            $table->tinyInteger("matching_type")->default(0);
        });
    }

    private function dropTable($field)
    {
        Schema::drop('matching_'.$field.'_config');
    }

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->createTable(BaseDataModel::ORGANIZATION);
        $this->createTable(BaseDataModel::JOB);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->dropTable(BaseDataModel::ORGANIZATION);
        $this->dropTable(BaseDataModel::JOB);
	}

}
