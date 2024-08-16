<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUniqueTitleFoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('folders', function(Blueprint $table)
		{
            $table->dropUnique('folders_title_unique');
            $table->unique(["title","role"]);
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
			$table->unique("title");
            $table->dropUnique('folders_title_role_unique');

        });
	}

}