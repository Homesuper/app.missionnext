<?php

use MissionNext\Models\Admin\AdminUserModel;

class AdminUsersSeeder extends BaseSeeder {

    public function run() {

        DB::table('adminusers')->truncate();

        $user = Sentry::createUser(array(
            'email'     => 'admin@loc.com',
            'password'  => '123456',  // Админпароль
            'activated' => true,
            'username'  => 'admin',
        ));

    }
}