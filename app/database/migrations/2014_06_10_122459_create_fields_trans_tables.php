<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateFieldsTransTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $this->createTransTable(BaseDataModel::CANDIDATE);
        $this->createTransTable(BaseDataModel::AGENCY);
        $this->createTransTable(BaseDataModel::ORGANIZATION);
        $this->createTransTable(BaseDataModel::JOB);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(BaseDataModel::CANDIDATE.'_fields_trans');
		Schema::drop(BaseDataModel::AGENCY.'_fields_trans');
		Schema::drop(BaseDataModel::ORGANIZATION.'_fields_trans');
		Schema::drop(BaseDataModel::JOB.'_fields_trans');
	}

    /**
     * @param $role
     */
    private function createTransTable($role)
    {

        Schema::create($role.'_fields_trans', function(Blueprint $table) use ($role)
        {
            $table->unsignedInteger('field_id');
            $table->foreign('field_id')->references('id')->on($role.'_fields')->onDelete('cascade');

            $table->unsignedInteger('lang_id');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');

            $table->string('name', 60);
        });
    }

}
