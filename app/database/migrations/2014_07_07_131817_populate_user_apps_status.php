<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PopulateUserAppsStatus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$apps = \MissionNext\Models\Application\Application::all()->lists('id');
        /** @var  $user \MissionNext\Models\User\User */
        foreach(\MissionNext\Models\User\User::all() as $user){
            $user->appsStatuses()->sync($apps);
        }

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DELETE FROM user_apps_status");
	}

}
