<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCahcedProfileTrans extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("
               CREATE TABLE candidate_cached_profile_trans
              (id serial NOT NULL,
               lang_id integer NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT candidate_user_trans_id_foreign FOREIGN KEY (id)
                  REFERENCES users (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE,
               CONSTRAINT candidate_cached_profile_trans_lang_id_foreign FOREIGN KEY (lang_id)
                  REFERENCES languages (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE
            )");
        DB::statement("
               CREATE TABLE organization_cached_profile_trans
              (id serial NOT NULL,
               lang_id integer NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT organization_user_trans_id_foreign FOREIGN KEY (id)
                  REFERENCES users (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE,
               CONSTRAINT organization_cached_profile_trans_lang_id_foreign FOREIGN KEY (lang_id)
                  REFERENCES languages (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE
            )");
        DB::statement("
              CREATE TABLE agency_cached_profile_trans
              (id serial NOT NULL,
               lang_id integer NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT agency_user_trans_id_foreign FOREIGN KEY (id)
                  REFERENCES users (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE,
               CONSTRAINT agency_cached_profile_trans_lang_id_foreign FOREIGN KEY (lang_id)
                  REFERENCES languages (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE
            )");
        DB::statement("
              CREATE TABLE job_cached_profile_trans
              (id serial NOT NULL,
               lang_id integer NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT job_user_trans_id_foreign  FOREIGN KEY (id)
                  REFERENCES jobs (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE,
               CONSTRAINT job_cached_profile_trans_lang_id_foreign FOREIGN KEY (lang_id)
                  REFERENCES languages (id) MATCH SIMPLE
                  ON UPDATE NO ACTION ON DELETE CASCADE
            )");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement("DROP TABLE IF EXISTS candidate_cached_profile_trans");
        DB::statement("DROP TABLE IF EXISTS organization_cached_profile_trans");
        DB::statement("DROP TABLE IF EXISTS agency_cached_profile_trans");
        DB::statement("DROP TABLE IF EXISTS job_cached_profile_trans");
	}

}
