<?php
Route::get('/db-test', function() {
    try {
        DB::connection()->getPdo();
        return 'Database connection is OK!';
    } catch (\Exception $e) {
        return 'Could not connect to the database. Error: ' . $e->getMessage();
    }
});