<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_groups', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string("symbol_key", 60);
            $table->string("name", 60);
            $table->smallInteger('order')->nullable();
            $table->text('meta')->nullable();
            $table->string('depends_on', 60)->nullable();
            $table->boolean('is_outer_dependent')->nullable();
            $table->unsignedInteger('form_id');
            $table->foreign("form_id")->references('id')->on('app_forms')->onDelete('cascade');
            $table->unique(['symbol_key', 'form_id']);
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
		Schema::drop('form_groups');
	}

}
