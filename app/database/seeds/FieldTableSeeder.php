<?php
use MissionNext\Models\Field\FieldType;

class FieldTableSeeder extends BaseSeeder
{
        public function run()
        {
            DB::statement($this->getDbStatement()->truncateTable("candidate_fields"));
            DB::statement($this->getDbStatement()->truncateTable("organization_fields"));
            DB::statement($this->getDbStatement()->truncateTable("agency_fields"));
            DB::statement($this->getDbStatement()->truncateTable("job_fields"));

            DB::table('job_fields')->insert(array(

                array(
                    "symbol_key" => "job_title",
                    "name" => "Job title",
                    "type" => FieldType::SELECT,
                ),

                array(
                    "symbol_key" => "second_title",
                    "name" => "Second Title",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "job_location",
                    "name" => "Job location",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "world_region",
                    "name" => "World region",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "position_type",
                    "name" => "Position Type",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "subject_speciality",
                    "name" => "Subject Speciality",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "alternate_speciality",
                    "name" => "Alternate Speciality",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "job_comment",
                    "name" => "Job Comment",
                    "type" => FieldType::TEXT,
                ),
                array(
                    "symbol_key" => "favourite_movies",
                    "name" => "Favourite movies",
                    "type" => FieldType::CHECKBOX,
                ),
                array(
                    "symbol_key" => "birth_date",
                    "name" => "Birth date",
                    "type" => FieldType::DATE,
                ),

            ));

            DB::table('candidate_fields')->insert(array(

                array(
                    "symbol_key" => "birth_date",
                    "name" => "Birth date",
                    "type" => FieldType::DATE,
                ),

                array(
                    "symbol_key" => "country",
                    "name" => "Country",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "zip_code",
                    "name" => "Zip Code",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "hobby",
                    "name" => "Hobby",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "occupation",
                    "name" => "Occupation",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "skype_handle",
                    "name" => "Skype Handle",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "day_phone",
                    "name" => "Day Phone",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "eve_phone",
                    "name" => "Eve Phone",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "mobile_phone",
                    "name" => "Mobile phone",
                    "type" => FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "best_way_to_contact",
                    "name" => "Best way to contact",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "about_me",
                    "name" => "About me",
                    "type" => FieldType::TEXT,
                ),
                array(
                    "symbol_key" => "agree_with_terms",
                    "name" => "Agree with terms of services",
                    "type" => FieldType::BOOLEAN,
                ),
                array(
                    "symbol_key" => "favourite_movies",
                    "name" => "Favourite movies",
                    "type" => FieldType::CHECKBOX,
                ),
                array(
                    "symbol_key" => "gender",
                    "name" => "gender",
                    "type" => FieldType::RADIO,
                ),
                array(
                    "symbol_key" => "resume",
                    "name" => "resume",
                    "type" => FieldType::FILE,
                ),
            ));

            DB::table('organization_fields')->insert(array(

                array(
                    "symbol_key" => "birth_date",
                    "name" => "Birth date",
                    "type" => 1,
                ),

                array(
                    "symbol_key" => "country",
                    "name" => "Country",
                    "type" => FieldType::SELECT,
                ),
                array(
                    "symbol_key" => "zip_code",
                    "name" => "Zip Code",
                    "type" =>  FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "hobby",
                    "name" => "Hobby",
                    "type" =>  FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "occupation",
                    "name" => "Occupation",
                    "type" =>  FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "first_name",
                    "name" => "First name",
                    "type" =>  FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "last_name",
                    "name" => "Last name",
                    "type" =>  FieldType::INPUT,
                ),
                array(
                    "symbol_key" => "favourite_movies",
                    "name" => "Favourite movies",
                    "type" => FieldType::CHECKBOX,
                ),
                array(
                    "symbol_key" => "gender",
                    "name" => "gender",
                    "type" => FieldType::RADIO,
                ),

                array(
                    "symbol_key" => "choose", //10
                    "name" => "choose",
                    "type" => FieldType::CHECKBOX,
                ),

            ));

            DB::table('agency_fields')->insert(array(

                array(
                    "symbol_key" => "birth_date",
                    "name" => "Birth date",
                    "type" => FieldType::DATE,
                ),

                array(
                    "symbol_key" => "country",
                    "name" => "Country",
                    "type" => FieldType::SELECT,
                ),

                array(
                    "symbol_key" => "first_name",
                    "name" => "First name",
                    "type" => FieldType::INPUT,
                ),

                array(
                    "symbol_key" => "last_name",
                    "name" => "Last name",
                    "type" => FieldType::INPUT,
                ),

                array(
                    "symbol_key" => "about_me",
                    "name" => "About me",
                    "type" => FieldType::TEXT,
                ),
                array(
                    "symbol_key" => "agree_with_terms",
                    "name" => "Agree with terms of services",
                    "type" => FieldType::BOOLEAN,
                ),
                array(
                    "symbol_key" => "favourite_movies",
                    "name" => "Favourite movies",
                    "type" => FieldType::CHECKBOX,
                ),
                array(
                    "symbol_key" => "gender",
                    "name" => "gender",
                    "type" => FieldType::RADIO,
                ),


            ));

       }
}