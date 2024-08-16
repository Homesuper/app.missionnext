<?php
use MissionNext\Models\Dictionary\Candidate as CanDictionary;
use MissionNext\Models\Dictionary\Organization as OrgDictionary;
use MissionNext\Models\Dictionary\Agency as AgDictionary;
use MissionNext\Models\Role\Role;
use MissionNext\Models\User\User;
use MissionNext\Models\Job\Job;

class UserProfileSeeder extends BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("candidate_profile"));
        DB::statement($this->getDbStatement()->truncateTable("organization_profile"));

        /** @var  $candidate User */
        $candidate = Role::find(Role::ROLE_CANDIDATE)->users()->first();
        /** @var  $org User */
        $org = Role::find(Role::ROLE_ORGANIZATION)->users()->first();
        /** @var  $org1 User */
        $org1 = User::find(5);
        /** @var  $agency User */
        $agency = Role::find(Role::ROLE_AGENCY)->users()->first();
        /** @var  $candidate1 User */
        $candidate1 = User::find(4);
        /** @var  $candidate10App2 User */
        $candidate10App2 = User::find(10);



        $choiceCan = CanDictionary::find(4);
        $choiceCan1 = CanDictionary::find(1);
        $choiceOrg = OrgDictionary::find(1);
        $choiceAg = AgDictionary::find(1);
        /** @var  $job1 Job */
        $job1 = Job::find(1);
        $job1->jobFields()->attach(5, ["value" => "Administrator" ]);
        $job1->jobFields()->attach(6, ["value" => "Computer & Keyboarding", "dictionary_id" => 11 ]);
        $job1->jobFields()->attach(2, ["value" => "Some title" ]);
        $job1->jobFields()->attach(9, ["value" => "Buratino" , "dictionary_id" => 15 ]);
        $job1->jobFields()->attach(9, ["value" => "Terminatorik", "dictionary_id" => 16  ]);
        $job1->jobFields()->attach(9, ["value" => "(!)Bamby's", "dictionary_id" => 14  ]);
        $job1->jobFields()->attach(3, ["value" => "Mexico" , "dictionary_id" => 13  ]);
        $job1->jobFields()->attach(10, ["value" => "1988-11-11" ]);

        /** @var  $job2 Job */
        $job2 = Job::find(2);
        $job2->jobFields()->attach(5, ["value" => "Administrator" ]);
        $job2->jobFields()->attach(6, ["value" => "Spanish" , "dictionary_id" => 1  ]);
        $job2->jobFields()->attach(2, ["value" => "Some title" ]);
        /** @var  $job3 Job */
        $job3 = Job::find(3);
        $job3->jobFields()->attach(5, ["value" => "Admin" ]); //Administrator -
        $job3->jobFields()->attach(6, ["value" => "Spanish" , "dictionary_id" => 1  ]); //Spanish +
        $job3->jobFields()->attach(2, ["value" => "Bamby's" , "dictionary_id" => 8  ]); // (!)bamby +
        $job3->jobFields()->attach(3, ["value" => "location" ]); //  +
        $job3->jobFields()->attach(4, ["value" => "Mexico" , "dictionary_id" => 13  ]); // not in config
        $job3->jobFields()->attach(10, ["value" => "1988-11-11" ]); //-
        $job3->jobFields()->attach(1, ["value" => "Another title" ]); // +
        $job3->jobFields()->attach(9, ["value" => "Paraska" ]); // favourite movies
        $job3->jobFields()->attach(9, ["value" => "Buryata" ]);

        /** @var  $job4 Job */
        $job4 = Job::find(4);
        $job4->jobFields()->attach(5, ["value" => "Administratorik" ]);
        $job4->jobFields()->attach(6, ["value" => "Spanish" ]);
        $job4->jobFields()->attach(2, ["value" => "Some title" ]);
        $job4->jobFields()->attach(9, ["value" => "Bamby" ]);
        $job4->jobFields()->attach(9, ["value" => "Buratino" ]);
        //$job4->jobFields()->attach(10, ["value" => "1990-11-11" ]);
        $job4->jobFields()->attach(1, ["value" => "Admin" ]);
        $job4->jobFields()->attach(3, ["value" => "Mexico" ]);

        /** @var  $job5 Job */
        $job5 = Job::find(5);
        $job5->jobFields()->attach(5, ["value" => "Administratorik" ]);
        $job5->jobFields()->attach(6, ["value" => "German" ]);
        $job5->jobFields()->attach(2, ["value" => "Another title" ]);
        $job5->jobFields()->attach(3, ["value" => $choiceCan1->value ]);
        $job5->jobFields()->attach(9, ["value" => "Bamby" ]);
        $job5->jobFields()->attach(9, ["value" => "Buratino" ]);

        // JOBS APP_2
        /** @var  $job7 Job */
        $job7 = Job::find(7);
        $job7->jobFields()->attach(5, ["value" => "Admin" ]); //Administrator -
        $job7->jobFields()->attach(6, ["value" => "Spanish" ]); //Spanish +
        $job7->jobFields()->attach(2, ["value" => "Bamby's" ]); // (!)bamby +
        $job7->jobFields()->attach(3, ["value" => "location" ]); //  +
        $job7->jobFields()->attach(4, ["value" => "Mexico" ]); // not in config
        $job7->jobFields()->attach(10, ["value" => "1988-11-11" ]); //-
        $job7->jobFields()->attach(1, ["value" => "Another title" ]); // +
        $job7->jobFields()->attach(9, ["value" => "Paraska" ]); // favourite movies
        $job7->jobFields()->attach(9, ["value" => "Buryata" ]);

        // END JOBS APP2


        /**
         * (job_id, can_id)
         * matching (1, 6), (3, 2), (5, 5), (6, 4), (2, 13), (10, 1), (9, 10)
         */

        $candidate->candidateFields()->attach(1, array('value' => "1990-11-11"));
        $candidate->candidateFields()->attach(2, array('value' => $choiceCan->value));
        $candidate->candidateFields()->attach(13, array('value' => "Bamby"));
        $candidate->candidateFields()->attach(13, array('value' => "Buratino"));
        $candidate->candidateFields()->attach(5, array('value' => "Administrator"));
        $candidate->candidateFields()->attach(4, array('value' => "English"));
        $candidate->candidateFields()->attach(6, array('value' => "Some title"));

       //4
        $candidate1->candidateFields()->attach(1, array('value' => "1977-11-11"));
        $candidate1->candidateFields()->attach(2, array('value' => "location" ));
        $candidate1->candidateFields()->attach(13, array('value' => "(!)Bamby" , "dictionary_id" => 8 ));
        $candidate1->candidateFields()->attach(13, array('value' => "Buratino" , "dictionary_id" => 9));
        $candidate1->candidateFields()->attach(5, array('value' => "Administrator" ));
        $candidate1->candidateFields()->attach(4, array('value' => "Spanish" , "dictionary_id" => 1));
        $candidate1->candidateFields()->attach(6, array('value' => "Another title"));
        $candidate1->candidateFields()->attach(10, array('value' => "ParaSkaBB"));//best way to contact
        $candidate1->candidateFields()->attach(3, array('value' => 110));
       //10 app2 candidate
        $candidate10App2->candidateFields()->attach(1, array('value' => "1977-11-11"));
        $candidate10App2->candidateFields()->attach(2, array('value' => "location" ));
        $candidate10App2->candidateFields()->attach(13, array('value' => "(!)Bamby"));
        $candidate10App2->candidateFields()->attach(13, array('value' => "Buratino"));
        $candidate10App2->candidateFields()->attach(5, array('value' => "Administrator"));
        $candidate10App2->candidateFields()->attach(4, array('value' => "Spanish"));
        $candidate10App2->candidateFields()->attach(6, array('value' => "Another title"));
        $candidate10App2->candidateFields()->attach(10, array('value' => "ParaSkaBB"));//best way to contact
        $candidate10App2->candidateFields()->attach(3, array('value' => 110));
        /**
         * (job_id, can_id)
         * matching (1, 6), (3, 2), (5, 5), (6, 4), (2, 13), (10, 1), (9, 10)
         */


        $org->organizationFields()->attach(1, array('value' => "1975-11-11"));
        $org->organizationFields()->attach(2, array('value' => $choiceOrg->value));
        $org->organizationFields()->attach(10, array('value' => 1, "dictionary_id" => 5 ));
        $org->organizationFields()->attach(10, array('value' => 2, "dictionary_id" => 6 ));
        $org->organizationFields()->attach(3, array('value' => 100));

        $org1->organizationFields()->attach(1, array('value' => "1976-11-11"));
        $org1->organizationFields()->attach(2, array('value' => $choiceOrg->value));
        $org1->organizationFields()->attach(3, array('value' => 140));

        $agency->agencyFields()->attach(1, array('value' => "1988-11-11"));
        $agency->agencyFields()->attach(2, array('value' => $choiceAg->value));


    }
}