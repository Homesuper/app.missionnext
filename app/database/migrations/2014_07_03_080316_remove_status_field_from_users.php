<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\User\User;

class RemoveStatusFieldFromUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('status');
		});
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
            $table->string('status', 60)->default(User::STATUS_PENDING_APPROVAL);

        });

        (new User)->update(['status' => User::STATUS_ACTIVE, 'is_active' => true]);
    }

}
