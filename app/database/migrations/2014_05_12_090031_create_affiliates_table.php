<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('affiliates', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('affiliate_approver');
            $table->foreign("affiliate_approver")->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('affiliate_requester');
            $table->foreign("affiliate_requester")->references('id')->on('users')->onDelete('cascade');
            $table->string('affiliate_approver_type', 30);
            $table->string("status", 30);//cancelled, pending, approved
            $table->unique(['affiliate_approver', 'affiliate_requester']);
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
		Schema::drop('affiliates');
	}

}
