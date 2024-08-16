<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnForUserTypeMatchingResults extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('matching_results', function(Blueprint $table)
		{
            $table->string('for_user_type', 30)->index()->default("");
            $table->tinyInteger('matching_percentage')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('matching_results', function(Blueprint $table)
		{
			$table->dropColumn('for_user_type');
			$table->dropColumn('matching_percentage');
		});
	}

}