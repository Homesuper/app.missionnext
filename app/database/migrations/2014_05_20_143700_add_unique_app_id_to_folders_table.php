<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueAppIdToFoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('folders', function(Blueprint $table)
		{
            $table->dropUnique('folders_title_role_unique');
            $table->unique(["title","role", "app_id"]);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('folders', function(Blueprint $table)
		{
            $table->unique(["title", "role"]);
            $table->dropUnique('folders_title_role_app_id_unique');
		});
	}

}