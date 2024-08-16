<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminuserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('adminusers', function($table)
        {
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('username');
            $table->string('password');
            $table->text('permissions')->nullable();
            $table->boolean('activated')->default(0);
            $table->string('activation_code')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('persist_code')->nullable();
            $table->string('reset_password_code')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->timestamps();
            $table->unique('email');
            $table->unique('username');
            $table->index('activation_code');
            $table->index('reset_password_code');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('adminusers');
	}

}
