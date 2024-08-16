<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FolderTransTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folder_trans', function(Blueprint $table)
		{
            $table->unsignedInteger('folder_id');
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');

            $table->unsignedInteger('lang_id');
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');

            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');

            $table->string("value", 100);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('folder_trans');
	}

}
