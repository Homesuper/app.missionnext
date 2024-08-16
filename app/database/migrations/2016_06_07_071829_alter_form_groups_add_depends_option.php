<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFormGroupsAddDependsOption extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('form_groups', function(Blueprint $table)
		{
			$table->string('depends_on_option', 60)->after('depends_on')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('form_groups', function(Blueprint $table)
		{
			$table->dropColumn('depends_on_option');
		});
	}

}
