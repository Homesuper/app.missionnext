<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormGroupsTransTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_groups_trans', function(Blueprint $table)
		{
            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('form_groups')->onDelete('cascade');

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
		Schema::drop('form_groups_trans');
	}

}
