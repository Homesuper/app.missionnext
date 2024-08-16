<?php



class SearchSeeder extends \BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("search_data"));

        DB::table('search_data')->insert(array(
            array(  'user_type' => 'candidate',
                    'search_name' => 'some search',
                    'search_type' => 'job',
                    'user_id' => 4,
                    'data' => json_encode(['profileData' => ['birth_date' => '1990-11-11']]),
                    'created_at' => "2000-11-11 19:00:00",
                    'updated_at' => "2000-11-11 19:00:00"
         )));
    }
} 