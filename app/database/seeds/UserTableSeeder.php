<?php

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends BaseSeeder
{
    public function run()
    {
        $dateTime = (new DateTime)->format("Y-m-d H:i:s");
        DB::statement($this->getDbStatement()->truncateTable("users"));

        DB::table('users')->insert(array(
            array('email' => 'admin@example.com', 'password' =>  Hash::make('123456'),
                  'username' => 'admin',
                  'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),
            array('email' => 'user@example.com', 'password' =>  Hash::make('654321'),
                  'username' => 'user',
                  'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),
            array('email' => 'org@example.com', 'password' =>  Hash::make('654321'),
                'username' => 'organizator',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),
            array('email' => 'candidate1@example.com', 'password' =>  Hash::make('654321'),
                'username' => 'candidate1',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),
            array('email' => 'organizator@example.com', 'password' =>  Hash::make('654321'),
                'username' => 'organizator1',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),

            array('email' => 'org2@example.com', 'password' =>  Hash::make('654321'),//6
                'username' => 'org2',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),

            array('email' => 'org3@example.com', 'password' =>  Hash::make('654321'),//7
                'username' => 'org3',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),

            array('email' => 'org4@example.com', 'password' =>  Hash::make('654321'),//8
                'username' => 'org4',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),

            array('email' => 'adminApp2@example.com', 'password' =>  Hash::make('654321'),//9
                'username' => 'adminApp2',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),

            array('email' => 'Candidate1App2@example.com', 'password' =>  Hash::make('654321'),//10
                'username' => 'Candidate1App2',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),

            array('email' => 'org1App2@example.com', 'password' =>  Hash::make('654321'), //11
                'username' => 'org1App2',
                'created_at'=>$dateTime, 'updated_at'=>$dateTime
            ),
        ));

    }
}