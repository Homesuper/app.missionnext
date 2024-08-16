<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminusersAdmingroupsPivot extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('adminusers_admingroups', function($table)
        {
            $table->integer('user_id')->unsigned();
            $table->integer('group_id')->unsigned();
            $table->primary(array('user_id', 'group_id'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('adminusers_admingroups');
	}

}
