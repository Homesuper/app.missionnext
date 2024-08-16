<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFoldersWithNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folders_with_notes', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('user_type', 30)->index();
            $table->integer('user_id')->index();
            $table->integer('for_user_id')->index();
            $table->text("notes")->nullable();
            $table->string("folder",50)->nullable();
            $table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('folders_with_notes');
	}

}
