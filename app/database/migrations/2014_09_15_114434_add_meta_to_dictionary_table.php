<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaToDictionaryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        foreach(\MissionNext\Models\DataModel\BaseDataModel::allRoles() as $role)
        {
            Schema::table($role.'_dictionary', function(Blueprint $table)
            {
                $table->text('meta')->default('');
            });
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        foreach(\MissionNext\Models\DataModel\BaseDataModel::allRoles() as $role)
        {
            Schema::table($role.'_dictionary', function(Blueprint $table)
            {
                $table->dropColumn('meta');
            });
        }
	}

}
