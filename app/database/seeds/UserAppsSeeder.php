<?php
use MissionNext\Models\User\User;

class UserAppsSeeder extends BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("user_apps"));

        User::find(3)->apps()->attach(1);
        User::find(2)->apps()->attach(1);
        User::find(1)->apps()->attach(1);
        User::find(4)->apps()->attach(1);
        User::find(5)->apps()->attach(1);

        User::find(6)->apps()->attach(1);
        User::find(7)->apps()->attach(1);
        User::find(8)->apps()->attach(1);

        User::find(9)->apps()->attach(2);
        User::find(10)->apps()->attach(2);
        User::find(11)->apps()->attach(2);
    }
} 