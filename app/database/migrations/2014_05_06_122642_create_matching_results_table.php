<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchingResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matching_results', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('user_type', 30)->index();
            $table->integer('user_id')->index();
            $table->integer('for_user_id')->index();
            $table->text("data")->nullable();

            $table->timestamps();
		});
        DB::statement('ALTER TABLE matching_results ALTER COLUMN data SET DATA TYPE json USING to_json(data)');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('matching_results');
	}

}
