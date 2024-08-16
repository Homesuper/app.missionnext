<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCacheTableAddIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('candidate_cached_profile', function($table){
            $table->index('id');
        });

        Schema::table('agency_cached_profile', function($table){
            $table->index('id');
        });

        Schema::table('organization_cached_profile', function($table){
            $table->index('id');
        });

        Schema::table('job_cached_profile', function($table){
            $table->index('id');
        });

        /* Candidate indexes */
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'first_name'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'last_name'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'marital_status'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'process_stage'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'gender'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'highest_earned_degree'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'degree_field'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'bible_training'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'cross-cultural_experience'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'occupation:'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'military_serivce?'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'attended_perspectives'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'languages'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'financial_support'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'financial_status'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'travel_support'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'relocation_possibilities'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'region_preferences'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'ministry_preferences'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'approximate_availability'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'time_availability'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'your_faith_journey'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'country'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'citizenship_country'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'formal_teaching_credentials'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'alternate_teaching_qualifications'))");
        DB::statement("CREATE INDEX ON candidate_cached_profile((data->'profileData'->>'preferred_education_positions'))");

        /* Organization indexes */
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'organization_name'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'abbreviation'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'country'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'english_skills'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'has_teaching_credentials'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'school_classroom_experience'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'school_formal_education_degree'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'time_commitments'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'affiliated_with_a_church'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'financial_support'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'travel_support'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'educational_positions'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'teacher_experience_preferred'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'regions_of_the_world'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'time_availability'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'languages'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'cross-cultural_experience'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'candidate_process_stages'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'attended_perspectives?'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'bible_school_training'))");
        DB::statement("CREATE INDEX ON organization_cached_profile((data->'profileData'->>'divorce_question'))");

        /* Job indexes */
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'job_title_!#explorenext'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'job_title_!#teachnext'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'world_region'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'languages'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'financial_support'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'travel_support_(to_and_from_school_location)'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'job_preferences'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'country'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'position_type'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'subject_speciality'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'teaching_degree_required'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'credentials_required'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'experience_as_educator'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'cross-cultural_experience'))");
        DB::statement("CREATE INDEX ON job_cached_profile((data->'profileData'->>'teaching_experience_required'))");

        /* Agency indexes */
        DB::statement("CREATE INDEX ON agency_cached_profile((data->'profileData'->>'agency_full_name'))");
        DB::statement("CREATE INDEX ON agency_cached_profile((data->'profileData'->>'abbreviation'))");
        DB::statement("CREATE INDEX ON agency_cached_profile((data->'profileData'->>'first_name'))");
        DB::statement("CREATE INDEX ON agency_cached_profile((data->'profileData'->>'last_name'))");
        DB::statement("CREATE INDEX ON agency_cached_profile((data->'profileData'->>'state'))");
        DB::statement("CREATE INDEX ON agency_cached_profile((data->'profileData'->>'country'))");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('candidate_cached_profile', function($table){
            $table->dropIndex('candidate_cached_profile_id_index');
        });

        Schema::table('agency_cached_profile', function($table){
            $table->dropIndex('agency_cached_profile_id_index');
        });

        Schema::table('organization_cached_profile', function($table){
            $table->dropIndex('organization_cached_profile_id_index');
        });

        Schema::table('job_cached_profile', function($table){
            $table->dropIndex('job_cached_profile_id_index');
        });

        DB::statement("DROP INDEX candidate_cached_profile_expr_idx");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx1");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx2");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx3");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx4");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx5");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx6");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx7");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx8");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx9");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx10");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx11");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx12");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx13");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx14");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx15");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx16");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx17");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx18");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx19");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx20");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx21");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx22");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx23");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx24");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx25");
        DB::statement("DROP INDEX candidate_cached_profile_expr_idx26");

        DB::statement("DROP INDEX organization_cached_profile_expr_idx");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx1");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx2");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx3");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx4");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx5");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx6");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx7");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx8");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx9");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx10");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx11");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx12");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx13");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx14");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx15");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx16");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx17");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx18");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx19");
        DB::statement("DROP INDEX organization_cached_profile_expr_idx20");

        DB::statement("DROP INDEX job_cached_profile_expr_idx");
        DB::statement("DROP INDEX job_cached_profile_expr_idx1");
        DB::statement("DROP INDEX job_cached_profile_expr_idx2");
        DB::statement("DROP INDEX job_cached_profile_expr_idx3");
        DB::statement("DROP INDEX job_cached_profile_expr_idx4");
        DB::statement("DROP INDEX job_cached_profile_expr_idx5");
        DB::statement("DROP INDEX job_cached_profile_expr_idx6");
        DB::statement("DROP INDEX job_cached_profile_expr_idx7");
        DB::statement("DROP INDEX job_cached_profile_expr_idx8");
        DB::statement("DROP INDEX job_cached_profile_expr_idx9");
        DB::statement("DROP INDEX job_cached_profile_expr_idx10");
        DB::statement("DROP INDEX job_cached_profile_expr_idx11");
        DB::statement("DROP INDEX job_cached_profile_expr_idx12");
        DB::statement("DROP INDEX job_cached_profile_expr_idx13");
        DB::statement("DROP INDEX job_cached_profile_expr_idx14");

        DB::statement("DROP INDEX agency_cached_profile_expr_idx");
        DB::statement("DROP INDEX agency_cached_profile_expr_idx1");
        DB::statement("DROP INDEX agency_cached_profile_expr_idx2");
        DB::statement("DROP INDEX agency_cached_profile_expr_idx3");
        DB::statement("DROP INDEX agency_cached_profile_expr_idx4");
        DB::statement("DROP INDEX agency_cached_profile_expr_idx5");
	}

}
