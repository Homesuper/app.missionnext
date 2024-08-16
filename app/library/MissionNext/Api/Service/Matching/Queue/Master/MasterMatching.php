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

use MissionNext\Repos\CachedData\UserCachedRepository;

abstract class MasterMatching
{
    /** @var  \Pheanstalk_Pheanstalk */
    public static $pheanstalk;

    protected  static function run($queueData)
    {
        $job = null;
        static::$pheanstalk = Queue::getPheanstalk();

        if (!empty(static::$pheanstalk->listTubes()) && $tube = static::$pheanstalk->listTubes()[0] ){

//            try{
//                $job = static::$pheanstalk->peekReady($tube);
//                return false;
//                // dd($job->getData());
//            }catch (\Pheanstalk_Exception_ServerException $e){
//                Queue::push(static::class, $queueData);
//            }

            Queue::push(static::class, $queueData);
        }
    }

    abstract protected function match($role);

    abstract public  function fire($job, $data);

    /**
     * @param $d
     * @param $role
     * @param $taskClass
     *
     * @return bool
     */
    protected function oneToOneMatch($d, $role, $taskClass )
    {
        $cacheRep = new UserCachedRepository($role);
        $ids = $cacheRep->all()->lists("id");
        foreach($ids as $id){
            $d["userId"] = $id;
            Queue::push($taskClass, $d);
        }

        return true;
    }

    /**
     * @param $userId
     * @param $forUserType
     * @param $userType
     */
    protected function clearCache($appId, $userId, $forUserType, $userType)
    {
        $builder =  Results::where("app_id","=",$appId)
            ->where("for_user_id","=", $userId)
            ->where("for_user_type","=", $forUserType)
            ->where("user_type","=", $userType);

        $builder->delete();

        $oppositeBuilder = Results::where("app_id","=", $appId)
            ->where("user_id", $userId)
            ->where("for_user_type", $userType)
            ->where("user_type", $forUserType);

        $oppositeBuilder->delete();
    }

} 