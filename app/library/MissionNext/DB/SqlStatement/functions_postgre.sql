
        CREATE OR REPLACE FUNCTION json_array_text(_j json)
            RETURNS text[] AS
            $$
            SELECT array_agg(elem::text)
            FROM json_array_elements(_j) AS elem
            $$
            LANGUAGE sql IMMUTABLE


--           //SELECT * FROM json_each((SELECT b FROM aa WHERE a = 4)) WHERE key = 'f1';
--         //        $statement = DB::select("
--                                           //        SELECT data->'profileData'->>'alternate_speciality' FROM user_cached_profiles
--                                           //        WHERE data->'profileData'->>'alternate_speciality' <> ''
--                                           //        ");
--                                           //        $statement = DB::select("
--           //        SELECT * FROM json_each_text( ( SELECT data->'profileData' FROM  user_cached_profile WHERE user_id = 3  ) )
--                                           //
--                                           //        ");
--
--                                           //        $statement = DB::select("
--                                           //        SELECT json_text(json_array_elements(CAST(data->'profileData'->>'alternate_speciality' AS JSON)))  as alik  FROM  user_cached_profile WHERE user_id = 3
--                                           //
--                                           //        ");
--                                           $statement = DB::select("
--                                           SELECT * FROM (( SELECT json_text(json_array_elements(CAST(data->'profileData'->>'alternate_speciality' AS JSON)))  as alik FROM user_cached_profile WHERE user_id = 3  )) as SDF
--                                           WHERE alik in ('pm','driver')
--
--                                           ");
--                                           $statement = DB::select("
--                                           SELECT * FROM (( SELECT json_text(json_array_elements((data->'profileData'->>'alternate_speciality')::json ))  as alik FROM user_cached_profile WHERE user_id = 3  )) as SDF
--                                           WHERE alik in ('pm','driver')
--
--                                           ");