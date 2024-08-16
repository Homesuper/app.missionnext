<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFolderAppsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folder_apps', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('user_type', 30)->index();
            $table->integer('user_id')->index();
            $table->integer('for_user_id')->index();
            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
            $table->string("folder", 50)->nullable();
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
		Schema::drop('folder_apps');
	}

}
