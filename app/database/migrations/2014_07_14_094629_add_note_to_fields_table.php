<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteToFieldsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $addNote = function($role){
            Schema::table($role.'_fields', function(Blueprint $table)
            {
                $table->text('note')->nullable();
            });
        };

        foreach(\MissionNext\Models\DataModel\BaseDataModel::allRoles() as $role){
            $addNote($role);
        }

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        $remNote = function($role){
            Schema::table($role.'_fields', function(Blueprint $table)
            {
                $table->dropColumn('note');
            });
        };

        foreach(\MissionNext\Models\DataModel\BaseDataModel::allRoles() as $role){
            $remNote($role);
        }

	}



}
