<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_fields', function(Blueprint $table)
		{
            $table->string("symbol_key", 60);
            $table->smallInteger('order')->nullable();
            $table->text('meta')->nullable();
            $table->unsignedInteger('group_id');
            $table->foreign("group_id")->references('id')->on('form_groups')->onDelete('cascade');
            $table->unique(['symbol_key', 'group_id']);
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
		Schema::drop('group_fields');
	}

}
