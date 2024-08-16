<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('subscription_configs', function(Blueprint $table)
		{
			$table->boolean('partnership_status')->default(true);
		});

        \MissionNext\Models\Subscription\SubConfig::where('partnership', '=', '')->update(['partnership_status' => false]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('subscription_configs', function(Blueprint $table)
		{
			$table->dropColumn('partnership_status');
		});
	}

}
