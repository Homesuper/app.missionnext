<?php


namespace MissionNext\Api\Service\Matching\Queue;


use Illuminate\Support\Facades\Queue;
use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Matching\Results;
use MissionNext\Models\User\User;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Api\Service\Matching\CandidateOrganizations as MatchCanOrgs;
use MissionNext\Repos\Matching\ConfigRepository;
use MissionNext\Api\Service\Matching\Queue\CandidateOrganizations as CanOrgsQueue;

class CandidateOrganizations extends QueueMatching
{
    protected $userType = BaseDataModel::ORGANIZATION;

    protected $forUserType = BaseDataModel::CANDIDATE;

    protected $matchingClass = MatchCanOrgs::class;

    protected $queueClass = CanOrgsQueue::class;

    public function fire($job, $data)
    {
        $userId = $data["userId"];
        $application = Application::find($data["appId"]);
        $matchingId = isset($data["matchingId"]) ? $data["matchingId"] : null;
        $offset = isset($data["offset"]) ? $data["offset"] : 0;
        $this->job = $job;

        $this->securityContext()->getToken()->setApp($application);

        $this->securityContext()->getToken()->setRoles([BaseDataModel::ORGANIZATION]);

        $configRepo = (new ConfigRepository())->setSecurityContext($this->securityContext());

        $config = $configRepo->configByCandidateOrganizations()->get();

        if (!$config->count()) {

            $job->delete();
            return [];
        }

        $matchingId ? $this->matchResult($userId, $matchingId, $config)
            : $this->matchResults($userId,  $config, $offset);
    }
} 