<?php

namespace MissionNext\Api\Service\Matching\Queue;

use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Job\Job;
use MissionNext\Models\Matching\Results;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\User;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Api\Service\Matching\JobCandidates as MatchJobCandidates;
use MissionNext\Repos\Matching\ConfigRepository;
use MissionNext\Api\Service\Matching\Queue\JobCandidates as JobCandidatesQueue;

class JobCandidates extends QueueMatching
{
    protected $userType = BaseDataModel::CANDIDATE;

    protected $forUserType = BaseDataModel::JOB;

    protected $matchingClass = MatchJobCandidates::class;

    protected $queueClass = JobCandidatesQueue::class;

    public function fire($job, $data)
    {
        $userId = $data["userId"];
        $jobItem = Job::find($userId);
        $application = Application::find($data['appId']);
        if ($jobItem) {
            $organization = User::find($jobItem['organization_id']);
            if ($organization) {
                $subscription = $organization->subscriptions()
                    ->where('status', Subscription::STATUS_ACTIVE)
                    ->where('app_id', $data["appId"])->first();

                if ($organization->isActive() && $organization->isActiveInApp($application) && $subscription) {
                    $matchingId = isset($data["matchingId"]) ? $data["matchingId"] : null;
                    $offset = isset($data["offset"]) ? $data["offset"] : 0;
                    $this->job = $job;

                    $this->securityContext()->getToken()->setApp($application);

                    $this->securityContext()->getToken()->setRoles([BaseDataModel::JOB]);

                    $configRepo = (new ConfigRepository())->setSecurityContext($this->securityContext());

                    $config = $configRepo->configByJobCandidates()->get();
                    if (!$config->count()) {

                        $job->delete();
                        return [];
                    }

                    $matchingId ? $this->matchResult($userId, $matchingId, $config)
                        : $this->matchResults($userId,  $config, $offset);
                } else {
                    $job->delete();
                }
            } else {
                $job->delete();
            }
        } else {
            $job->delete();
        }
    }
} 