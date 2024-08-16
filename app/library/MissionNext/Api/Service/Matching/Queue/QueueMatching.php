<?php

namespace MissionNext\Api\Service\Matching\Queue;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Queue;
use MissionNext\Facade\SecurityContext;
use MissionNext\Api\Service\Matching\CandidateOrganizations as MatchCanOrgs;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Api\Service\Matching\Matching as ServiceMatching;
use MissionNext\Models\Matching\Results;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\User;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Repos\FormGroup\FormGroupRepository;

abstract class QueueMatching
{

    protected $userType,
        $forUserType,
        $matchingClass,
        $queueClass,
        $job;



    const QUERY_LIMIT = 5;
    /**
     * @return \MissionNext\Api\Auth\SecurityContext
     */
    protected  function securityContext()
    {


        return SecurityContext::getInstance();
    }

    /**
     * @param $userId
     * @param $matchingId
     * @param $config
     */
    protected function matchResult($userId, $matchingId ,$config)
    {
        try{
            $mainData = (new UserCachedRepository($this->forUserType))->mainData($userId)->getData();
        }
        catch (ModelNotFoundException $e){

            $this->job->delete();
            return [];
        }
        //=========

        try{
            $matchingData = [(new UserCachedRepository($this->userType))->mainData($matchingId)->getData()];
        }
        catch (ModelNotFoundException $e){
            $this->job->delete();
            return [];
        }

        $app_id = $this->securityContext()->getApp()->id;

        $data = [
            "mainData"          => $mainData,
            "matchingData"      => $matchingData,
            "matchingClass"     => $this->matchingClass,
            "forUserType"       => $this->forUserType,
            "userType"          => $this->userType,
            "config"            => $config->toArray(),
            "userId"            => $userId,
            "app_id"            => $app_id
        ];

        Queue::push(InsertQueue::class, $data);

        $this->job->delete();
    }

    /**
     * @param $userId
     * @param $config
     * @param $offset
     * @param $last_login
     */
    protected function matchResults($userId, $config, $offset, $last_login = null)
    {

        try{
            $mainData = (new UserCachedRepository($this->forUserType))->mainData($userId)->getData();
        }
        catch (ModelNotFoundException $e){
            $this->job->delete();
            return [];
        }

        $cacheRep = new UserCachedRepository($this->userType);

        $timelimit = null;
        if (BaseDataModel::JOB == $this->userType) {
            $date_limit = new \DateTime('now');
            $date_limit->modify("-6 months");
            $timelimit = $date_limit->getTimestamp();
        }
        $limit = static::QUERY_LIMIT;

        $app_id = $this->securityContext()->getApp()->id;

        $matchingData = $cacheRep->data($last_login, $timelimit)
            ->takeAndSkip($limit, $offset)
            ->get()
            ->toArray();

        if(!empty($matchingData)) {

            $offset += $limit;

            $tempMatchData = [];
            foreach ($matchingData as $data) {
                switch ($data['role']) {
                    case BaseDataModel::CANDIDATE:
                        $tempMatchData[] = $data;
                        break;
                    case BaseDataModel::ORGANIZATION:
                    case BaseDataModel::AGENCY:
                        $organization_id = $data['id'];
                        $user = User::find($organization_id);
                        if ($user) {
                            $subscription = $user->subscriptions()
                                ->where('status', Subscription::STATUS_ACTIVE)
                                ->where('app_id', $app_id)->first();

                            if ($user->isActive() && $user->isActiveInApp(Application::find($app_id)) && $subscription) {
                                $tempMatchData[] = $data;
                            }
                        }
                        break;
                    case BaseDataModel::JOB:
                        $organization_id = $data['organization']['id'];
                        $organization = User::find($organization_id);
                        if ($organization) {
                            $subscription = $organization->subscriptions()
                                ->where('status', Subscription::STATUS_ACTIVE)
                                ->where('app_id', $app_id)->first();

                            if ($organization->isActive() && $organization->isActiveInApp(Application::find($app_id)) && $subscription) {
                                $tempMatchData[] = $data;
                            }
                        }
                        break;
                }
            }

            if (count($tempMatchData) > 0) {
                $matchingData = $tempMatchData;
                $data = [
                    "mainData" => $mainData,
                    "matchingData" => $matchingData,
                    "matchingClass" => $this->matchingClass,
                    "forUserType" => $this->forUserType,
                    "userType" => $this->userType,
                    "config" => $config->toArray(),
                    "userId" => $userId,
                    "app_id" => $app_id
                ];

                Queue::push(InsertQueue::class, $data);
            }

            $startData = [
                "userId" => $userId,
                "appId" => $app_id,
                "role" => $this->forUserType,
                "offset" => $offset
            ];

            Queue::push($this->queueClass, $startData);
        }

        $this->job->delete();
    }
}
