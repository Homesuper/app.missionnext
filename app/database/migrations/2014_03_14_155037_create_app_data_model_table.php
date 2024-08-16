<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppDataModelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('app_data_model', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('type',60);
            $table->unsignedInteger('app_id')->nullable();
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('app_data_model');
	}

}
