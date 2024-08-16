<?php

use MissionNext\Models\Matching\Config;
use MissionNext\Facade\SecurityContext as Fsc;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Repos\RepositoryContainerInterface;

class MatchConfigSeeder extends BaseSeeder
{

    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("matching_job_config"));
        DB::statement($this->getDbStatement()->truncateTable("matching_organization_config"));
        DB::statement($this->getDbStatement()->truncateTable("organization_cached_profile"));
        DB::statement($this->getDbStatement()->truncateTable("job_cached_profile"));
        DB::statement($this->getDbStatement()->truncateTable("agency_cached_profile"));
        DB::statement($this->getDbStatement()->truncateTable("candidate_cached_profile"));
        /** @var  $sc SecurityContext */
        $sc = Fsc::getInstance();

        $sc->setToken((new \MissionNext\Api\Auth\Token())->setRoles([BaseDataModel::JOB])
        ->setApp(\MissionNext\Models\Application\Application::find(1)));

        $scApp2 = clone $sc;

        $scApp2->setToken((new \MissionNext\Api\Auth\Token())->setRoles([BaseDataModel::JOB])
            ->setApp(\MissionNext\Models\Application\Application::find(2)));

        $configRepApp2 = (new \MissionNext\Repos\Matching\ConfigRepository())->setSecurityContext($scApp2);

        $configRep = (new \MissionNext\Repos\Matching\ConfigRepository())->setSecurityContext($sc);
        /**
         * (job_id, can_id)
         * matching (1, 6), (3, 2), (5, 5), (6, 4), (2, 13), (10, 1)
         */

        $configs = $this->getJobConfig(1);
        $configRep->insert($configs);

        $configRepApp2->insert($this->getJobConfig(2));
        /** @var  $repoContainer \MissionNext\Repos\RepositoryContainer */
        $repoContainer = \Illuminate\Support\Facades\App::make(RepositoryContainerInterface::class);
        $repoContainer->setSecurityContext($scApp2);
        /** @var  $profileRepo  \MissionNext\Repos\User\ProfileRepositoryFactory */
        $profileRepo = $repoContainer[\MissionNext\Repos\User\ProfileRepositoryFactory::KEY];
        $userRepApp2 = $profileRepo->setRepoContainer($repoContainer)->profileRepository();

        $userRepApp2->addUserCachedData(Job::find(7));
        $userRepApp2->addUserCachedData(Job::find(8));
        $userRepApp2->addUserCachedData(Job::find(9));


        /**
         * set profile cache data
         */
        $repoContainer->setSecurityContext($sc);
        $profileRepo->setRepoContainer($repoContainer);
        $userRep = $profileRepo->profileRepository();
        $userRep->addUserCachedData(Job::find(1));

        $userRep->addUserCachedData(Job::find(2));
        $userRep->addUserCachedData(Job::find(3));
        $userRep->addUserCachedData(Job::find(4));
        $userRep->addUserCachedData(Job::find(5));
        $userRep->addUserCachedData(Job::find(6));

        $sc->getToken()->setRoles([BaseDataModel::CANDIDATE]);
        $scApp2->getToken()->setRoles([BaseDataModel::CANDIDATE]);

        $repoContainer->setSecurityContext($sc);
        $profileRepo->setRepoContainer($repoContainer);
        $userRep = $profileRepo->profileRepository();
        $userRep->addUserCachedData($userRep->find(2));
        $userRep->addUserCachedData($userRep->find(4));
        $repoContainer->setSecurityContext($scApp2);
        $profileRepo->setRepoContainer($repoContainer);
        $userRepApp2 = $profileRepo->profileRepository();
        $userRepApp2->addUserCachedData($userRepApp2->find(10));


        $sc->getToken()->setRoles([BaseDataModel::AGENCY]);
        $scApp2->getToken()->setRoles([BaseDataModel::AGENCY]);

        $repoContainer->setSecurityContext($sc);
        $profileRepo->setRepoContainer($repoContainer);
        $userRep = $profileRepo->profileRepository();
        $userRep->addUserCachedData($userRep->find(1));

        $repoContainer->setSecurityContext($scApp2);
        $profileRepo->setRepoContainer($repoContainer);
        $userRepApp2 = $profileRepo->profileRepository();
        $userRepApp2->addUserCachedData($userRepApp2->find(9));

        $sc->getToken()->setRoles([BaseDataModel::ORGANIZATION]);
        $scApp2->getToken()->setRoles([BaseDataModel::ORGANIZATION]);

        $repoContainer->setSecurityContext($sc);
        $profileRepo->setRepoContainer($repoContainer);
        $userRep = $profileRepo->profileRepository();

        $userRep->addUserCachedData($userRep->find(3));
        $userRep->addUserCachedData($userRep->find(5));
        $userRep->addUserCachedData($userRep->find(6));
        $userRep->addUserCachedData($userRep->find(7));
        $userRep->addUserCachedData($userRep->find(8));

        $repoContainer->setSecurityContext($scApp2);
        $profileRepo->setRepoContainer($repoContainer);
        $userRepApp2 = $profileRepo->profileRepository();

        $userRepApp2->addUserCachedData($userRepApp2->find(11));


        $configRep = (new \MissionNext\Repos\Matching\ConfigRepository())->setSecurityContext($sc);
        $orgConfig = $this->getOrgConfig(1);
        $configRep->insert($orgConfig);

        $scApp2->getToken()->setRoles([BaseDataModel::ORGANIZATION]);
        $configRepApp2 = (new \MissionNext\Repos\Matching\ConfigRepository())->setSecurityContext($scApp2);
        $configRepApp2->insert($this->getOrgConfig(2));

        $queueData = ["appId"=>1, "role" => BaseDataModel::CANDIDATE, "userId" => 0];

        \MissionNext\Api\Service\Matching\Queue\Master\ConfigUpdateMatching::run($queueData);


    }

    /**
     * @param $appId
     *
     * @return array
     */
    private function getJobConfig($appId)
    {
        return [
            [
                "matching_type" => Config::MATCHING_EQUAL,
                "weight" => 4,
                "matching_field_id" => 1, //"job_title" - select
                "main_field_id" => 6, // skype_handle - input
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_EQUAL,
                "weight" => 5,
                "matching_field_id" => 3, // job_location - input - job
                "main_field_id" => 2, // country - select - candidate
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_LIKE,
                "weight" => 4,
                "matching_field_id" => 5,//position type
                "main_field_id" => 5,//occupation
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_EQUAL,
                "weight" => 4,
                "matching_field_id" => 6, //subject_speciality
                "main_field_id" => 4, // hobby
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_EQUAL,
                "weight" => 4,
                "matching_field_id" => 2, //favourite_movies - checkbox - job
                "main_field_id" => 13,//favourite_movies - checkbox - candidate
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_LESS, //candidate less than job
                "weight" => 4,
                "matching_field_id" => 10, //birth_date - date -job
                "main_field_id" => 1, // birth_date - candidate
                "app_id" => $appId
            ],

            [
                "matching_type" => Config::MATCHING_EQUAL, //job less than candidate
                "weight" => 4,
                "matching_field_id" => 9, //favourite movies - multiple -job
                "main_field_id" => 10, // best way to contact - select single -  candidate
                "app_id" => $appId
            ],

        ];
    }

    /**
     * @param $appId
     *
     * @return array
     */
    private function getOrgConfig($appId)
    {
        return [
            [
                "matching_type" => Config::MATCHING_GREATER_OR_EQUAL, //canidadate date greater or equal
                "weight" => 3,
                "matching_field_id" => 1, //"birth_date" - date
                "main_field_id" => 1, // birth_date - date
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_EQUAL,
                "weight" => 4,
                "matching_field_id" => 2, //"contry" -
                "main_field_id" => 2, // country -
                "app_id" => $appId
            ],
            [
                "matching_type" => Config::MATCHING_LESS,
                "weight" => 4,
                "matching_field_id" => 3, //"zipcode" -
                "main_field_id" => 3, // zipcode -
                "app_id" => $appId
            ],
        ];
    }
}























