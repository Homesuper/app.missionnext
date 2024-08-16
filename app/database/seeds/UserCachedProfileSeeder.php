<?php


class UserCachedProfileSeeder extends BaseSeeder
{
    public function run()
    {

        DB::statement("
        CREATE OR REPLACE FUNCTION json_array_text(_j json)
            RETURNS text[] AS
            $$
            SELECT array_agg(LOWER(elem::text))
            FROM json_array_elements(_j) AS elem
            $$
            LANGUAGE sql IMMUTABLE"
        );

    }
}