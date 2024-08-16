<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserCachedProfileTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //if (DB::getDefaultConnection() === \MissionNext\DB\SqlStatement\Sql::PostgreSQL) {
            DB::statement("
               CREATE TABLE candidate_cached_profile
              (id serial NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT candidate_user_id_unique UNIQUE  (id)
            )");
            DB::statement("
               CREATE TABLE organization_cached_profile
              (id serial NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT organization_user_id_unique UNIQUE  (id)
            )");
            DB::statement("
              CREATE TABLE agency_cached_profile
              (id serial NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT agency_user_id_unique UNIQUE  (id)
            )");
            DB::statement("
              CREATE TABLE job_cached_profile
              (id serial NOT NULL,
               data json,
               created_at timestamp without time zone NOT NULL,
               updated_at timestamp without time zone NOT NULL,
               CONSTRAINT job_user_id_unique UNIQUE  (id)
            )");

           // DB::statement(" CREATE UNIQUE INDEX profile_data_symbol_key ON user_cached_profile ((data->'profileData'->>'symbol_key')) ");
     //   }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      //  if (DB::getDefaultConnection() === \MissionNext\DB\SqlStatement\Sql::PostgreSQL) {
            DB::statement("DROP TABLE IF EXISTS candidate_cached_profile");
            DB::statement("DROP TABLE IF EXISTS organization_cached_profile");
            DB::statement("DROP TABLE IF EXISTS agency_cached_profile");
            DB::statement("DROP TABLE IF EXISTS job_cached_profile");
       // }
    }

}
