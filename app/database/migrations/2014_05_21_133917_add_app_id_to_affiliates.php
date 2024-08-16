<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppIdToAffiliates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('affiliates', function(Blueprint $table)
		{
            $table->unsignedInteger('app_id')->default(1);
            $table->foreign("app_id")->references('id')->on('application')->onDelete('cascade');
            $table->dropUnique("affiliates_affiliate_approver_affiliate_requester_unique");

            $table->unique(['affiliate_approver', 'affiliate_requester', 'app_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('affiliates', function(Blueprint $table)
		{
            $table->dropForeign("affiliates_app_id_foreign");
            $table->dropColumn("app_id");
            $table->unique(['affiliate_approver', 'affiliate_requester']);

            $table->dropUnique("affiliates_affiliate_approver_affiliate_requester_app_idunique");

        });
	}

}