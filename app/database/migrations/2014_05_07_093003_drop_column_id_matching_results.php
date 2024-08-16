<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnIdMatchingResults extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('matching_results', function(Blueprint $table)
		{
                $table->dropPrimary('matching_results_pkey');
                $table->dropColumn('id');
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
            $table->primary('id');
		});
	}

}