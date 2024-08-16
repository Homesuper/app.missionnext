<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmingroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('admingroups', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->text('permissions')->nullable();
            $table->timestamps();
            $table->unique('name');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('admingroups');
	}

}
