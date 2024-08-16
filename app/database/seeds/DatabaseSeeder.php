<?php

class DatabaseSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard(); // allows mass assignment

        $dbStatement = $this->getDbStatement();

        //DB::statement($dbStatement->foreignKeyChecksOff());

        $this->runSeeders();

        //DB::statement($dbStatement->foreignKeyChecksOn());

    }

    private function runSeeders()
    {
        $this->call('ApplicationSeeder');
        $this->command->info('Application table seeded!');

        $this->call('UserTableSeeder');
        $this->command->info('User table seeded!');

        $this->call('JobTableSeeder');
        $this->command->info('Job table seeded!');

        $this->call('RoleTableSeeder');
        $this->command->info('Role table seeded!');

        $this->call('UserRoleTableSeeder');
        $this->command->info('UserRole table seeded!');

        $this->call('UserAppsSeeder');
        $this->command->info('UserApps table seeded!');

        $this->call('FieldTypeSeeder');
        $this->command->info('Field table seeded!');

        $this->call('FieldTableSeeder');
        $this->command->info('Field table seeded!');

        $this->call('DictionarySeeder');
        $this->command->info('Dictionary table seeded!');

        $this->call('DataModelFieldSeeder');
        $this->command->info('DataModelFieldSeeder table seeded!');

        $this->call('AppFormSeeder');
        $this->command->info('AppFormSeeder table seeded!');

        $this->call('UserProfileSeeder');
        $this->command->info('UserProfileSeeder table seeded!');

        $this->call('MatchConfigSeeder');
        $this->command->info('MatchConfigSeeder table seeded!');

        $this->call('SearchSeeder');
        $this->command->info('SearchSeeder table seeded!');

        $this->call('AdminUsersSeeder');
        $this->command->info('Admin Users seeded!');

        $this->call('UserCachedProfileSeeder');
        $this->command->comment('UserCachedProfileSeeder table seeded!');

    }

}