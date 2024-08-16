<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('search_data', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_type', 30)->index();
			$table->string('search_name', 40);
			$table->string('search_type', 30)->index();
			$table->integer('user_id')->index();
            $table->text("data");
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
		Schema::drop('search_data');
	}

}
