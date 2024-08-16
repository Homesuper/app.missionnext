<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppForms extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('app_forms', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string("symbol_key", 60);
            $table->string("name", 60);
            $table->unsignedInteger('data_model_id');
            $table->foreign("data_model_id")->references('id')->on('app_data_model')->onDelete('cascade');
            $table->unique(['symbol_key', 'data_model_id']);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('app_forms');
	}

}
