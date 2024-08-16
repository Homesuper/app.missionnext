<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\User\User;

class AddRegistrationStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('status', 60)->default(User::STATUS_PENDING_APPROVAL);
			$table->boolean('is_active')->default(false);

		});

        (new User)->update(['status' => 0, 'is_active' => true]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('status');
			$table->dropColumn('is_active');
		});
	}

}
