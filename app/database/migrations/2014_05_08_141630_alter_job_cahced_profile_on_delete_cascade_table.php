<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterJobCahcedProfileOnDeleteCascadeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('job_cached_profile', function(Blueprint $table)
		{
            DB::statement('
            ALTER TABLE job_cached_profile
            DROP CONSTRAINT job_user_id_unique,
            ADD CONSTRAINT  job_user_id_foreign
                FOREIGN KEY (id)
                REFERENCES jobs(id)
                ON DELETE CASCADE
            ');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('job_cached_profile', function(Blueprint $table)
		{
            DB::statement('
            ALTER TABLE job_cached_profile
            DROP CONSTRAINT job_user_id_foreign,
            ADD CONSTRAINT  job_user_id_unique
                UNIQUE (id)
            ');

        });
	}

}