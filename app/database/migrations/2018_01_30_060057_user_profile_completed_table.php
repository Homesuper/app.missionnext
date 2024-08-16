<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserProfileCompletedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('user_profile_completed', function(Blueprint $table)
        {
            $table->bigInteger('user_id');
            $table->integer('app_id');
            $table->string('role');
            $table->boolean('completed');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('user_profile_completed');
	}

}
