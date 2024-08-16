<?php


namespace MissionNext\Api\Service\Matching\Queue\Master;

use Illuminate\Support\Facades\Queue;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Facade\SecurityContext as fs;
use MissionNext\Filter\RouteSecurityFilter;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Api\Service\Matching\Queue\CandidateJobs as CanJobsQueue;
use MissionNext\Api\Service\Matching\Queue\CandidateOrganizations as CanOrgsQueue;
use MissionNext\Api\Service\Matching\Queue\OrganizationCandidates as OrgCandidatesQueue;
use MissionNext\Api\Service\Matching\Queue\JobCandidates as JobCandidatesQueue;
use MissionNext\Models\Matching\Results;

class ProfileUpdateMatching extends MasterMatching
{
    protected  function match($role)
    {
        switch($role){
            case BaseDataModel::CANDIDATE:
                return
                    function($data)
                    {
                        $this->clearCache($data['appId'], $data['userId'], 'candidate', 'organization');
                        $this->clearCache($data['appId'], $data['userId'], 'candidate', 'job');
                        Queue::push(CanOrgsQueue::class, $data);
                        Queue::push(CanJobsQueue::class, $data);
                    };
                break;
            case BaseDataModel::ORGANIZATION:
                return
                    function($data){
                        $this->clearCache($data['appId'], $data['userId'], 'organization', 'candidate');
                        Queue::push(OrgCandidatesQueue::class, $data);
                    };
                break;
            case BaseDataModel::JOB:
                return
                    function($data){
                        $this->clearCache($data['appId'], $data['userId'], 'job', 'candidate');
                        Queue::push(JobCandidatesQueue::class, $data);
                    };
                break;
            default:
                return
                    function($data){

                    };
        }
    }

    public static function run($queueData)
    {
        parent::run($queueData);
    }

    public function fire($job, $data)
    {
        // Results::truncate();
        $role = $data["role"];

        $m = $this->match($role);
        $m($data);
        $job->delete();
    }
}