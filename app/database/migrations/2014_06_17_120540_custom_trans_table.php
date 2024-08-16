<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomTransTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('custom_trans', function(Blueprint $table)
		{
            $table->string('key');
            $table->string("value", 100);
            $table->unsignedInteger('lang_id')->nullable();
            $table->foreign('lang_id')->references('id')->on('languages')->onDelete('cascade');
            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
            $table->index('key');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('custom_trans');
	}

}
