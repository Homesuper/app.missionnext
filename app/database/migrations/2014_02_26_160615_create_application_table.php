<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('application', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
            $table->string('public_key', 100)->unique();
            $table->string('private_key', 100)->unique();
            $table->string('name', 100)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('application');
	}

}
