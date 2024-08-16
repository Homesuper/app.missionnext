<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterGroupFieldsMetaToJson extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('group_fields', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE group_fields ALTER COLUMN meta SET DATA TYPE json USING to_json(meta)');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('group_fields', function(Blueprint $table)
		{
            DB::statement('ALTER TABLE group_fields ALTER COLUMN meta SET DATA TYPE text');

        });
	}

}