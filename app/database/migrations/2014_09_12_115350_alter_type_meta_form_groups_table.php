<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypeMetaFormGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        DB::statement('ALTER TABLE form_groups ALTER COLUMN meta SET DATA TYPE json USING to_json(meta)');

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement('ALTER TABLE form_groups ALTER COLUMN meta SET DATA TYPE text');

    }

}
