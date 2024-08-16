<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropKeysNotes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('notes', function(Blueprint $table)
		{
            $table->dropForeign('folders_with_notes_pkey');
            $table->dropIndex('folders_with_notes_for_user_id_index');
            $table->dropIndex('folders_with_notes_user_id_index');
            $table->dropIndex('folders_with_notes_user_type_index');

            $table->primary('id');
            $table->index('for_user_id');
            $table->index('user_id');
            $table->index('user_type');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notes', function(Blueprint $table)
		{
            $table->dropPrimary('notes_pkey');
            $table->dropIndex('notes_for_user_id_index');
            $table->dropIndex('notes_user_id_index');
            $table->dropIndex('notes_user_type_index');

//            $table->primary('id');
//            $table->index('for_user_id');
//            $table->index('user_id');
//            $table->index('user_type');
		});
	}

}