<?php

class DictionarySeeder extends BaseSeeder
{
    public function run()
    {

        DB::statement($this->getDbStatement()->truncateTable("agency_dictionary"));
        DB::statement($this->getDbStatement()->truncateTable("organization_dictionary"));
        DB::statement($this->getDbStatement()->truncateTable("candidate_dictionary"));
        DB::statement($this->getDbStatement()->truncateTable("job_dictionary"));


        DB::table('job_dictionary')->insert(array(
            array(
                "field_id" => 1,
                "value" => "Arts - Performing",
            ),
            array(
                "field_id" => 1,
                "value" => "Administrator",
            ),
            array(
                "field_id" => 1,
                "value" => "Art Teacher",
            ),
            array(
                "field_id" => 4,
                "value" => "Africa",
            ),
            array(
                "field_id" => 4,
                "value" => "Americas",
            ),
            array(
                "field_id" => 4,
                "value" => "Americas",
            ),
            array(
                "field_id" => 5,
                "value" => "Administration",
            ),
            array(
                "field_id" => 5,
                "value" => "Arts - Performing",
            ),
            array(
                "field_id" => 5,
                "value" => "Arts - Visual",
            ),
            array(
                "field_id" => 6,
                "value" => "Bible",
            ),
            array(
                "field_id" => 6,
                "value" => "Computer & Keyboarding",
            ),
            array(
                "field_id" => 7,
                "value" => "English",
            ),
            array(
                "field_id" => 7,
                "value" => "ESL",
            ),
            array(
                "field_id" => 9, //14
                "value" => "(!)Bamby's",
            ),
            array(
                "field_id" => 9,
                "value" => "Buratino",
            ),
            array(
                "field_id" => 9,
                "value" => "Terminatorik",
            ),

        ));
        DB::table('job_dictionary_trans')->insert(array(
            array(
                "dictionary_id" => 14,
                "lang_id" => 1,
                "value" => "First Lang (!)Bambys",
            ),
            array(
                "dictionary_id" => 16,
                "lang_id" => 1,
                "value" => "Pervuia Lang Terminatorik",
            ),
            array(
                "dictionary_id" => 15,
                "lang_id" => 2,
                "value" => "Second Buration",
            ),
            array(
                "dictionary_id" => 16,
                "lang_id" => 3,
                "value" => "Third Terminatorik",
            ),

        ));


        DB::table('candidate_dictionary')->insert(array(
            array(
                "field_id" => 2,
                "value" => "Argentina",
            ),
            array(
                "field_id" => 2,
                "value" => "Canada",
            ),
            array(
                "field_id" => 2,
                "value" => "Monaco",
            ),
            array(
                "field_id" => 2,
                "value" => "Mexico",
            ),
            array(
                "field_id" => 10,
                "value" => "By email",
            ),
            array(
                "field_id" => 10,
                "value" => "By phone",
            ),
            array(
                "field_id" => 10,
                "value" => "By phone",
            ),
            array(
                "field_id" => 13,
                "value" => "(!)Bamby's",
            ),
            array(
                "field_id" => 13,
                "value" => "Buratino",
            ),
            array(
                "field_id" => 14,
                "value" => "male",
            ),
            array(
                "field_id" => 14,
                "value" => "female",
            ),

        ));

        DB::table('organization_dictionary')->insert(array(
            array(
                "field_id" => 2,
                "value" => "Spain",
            ),
            array(
                "field_id" => 2,
                "value" => "France",
            ),
            array(
                "field_id" => 2,
                "value" => "Ecuador",
            ),
            array(
                "field_id" => 2,
                "value" => "Germany",
            ),

            array(
               "field_id" => 10,
               "value" => 1,
            ),
            array(
                 "field_id" => 10,
                 "value" => 2,
            ),
            array(
                "field_id" => 10,
                "value" => 3,
            )

        ));

        DB::table('agency_dictionary')->insert(array(
            array(
                "field_id" => 2,
                "value" => "India",
            ),
            array(
                "field_id" => 2,
                "value" => "Japan",
            ),
            array(
                "field_id" => 2,
                "value" => "Italy",
            ),

        ));


    }
}