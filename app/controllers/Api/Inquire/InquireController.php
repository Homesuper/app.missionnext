<?php


namespace MissionNext\Controllers\Api\Inquire;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Inquire\Inquire;
use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User;
use MissionNext\Repos\Inquire\InquireRepository;
use MissionNext\Repos\Inquire\InquireRepositoryInterface;

/**
 * Class InquireController
 *
 * @package MissionNext\Controllers\Api\Inquire
 */
class InquireController extends BaseController
{
    /**
     * @param User $candidate
     * @param Job $job
     *
     * @return RestResponse
     */
    public function postIndex(User $candidate, Job $job)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];

        return new RestResponse($repo->inquire($candidate, $job));
    }

    /**
     * @param User $candidate
     * @param Job $job
     *
     * @return RestResponse
     */
    public function postCancel(User $candidate, Job $job)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];

        return new RestResponse($repo->cancelJobByCandidate($job, $candidate));
    }

    /**
     * @param $inquireId
     * @param User $agency
     *
     * @return RestResponse
     */
    public function postCancelInquireByAgency($inquireId, User $agency)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];

        return new RestResponse($repo->cancelInquireByAgency(Inquire::findOrFail($inquireId), $agency));
    }

    /**
     * @param $inquireId
     * @param User $organization
     *
     * @return RestResponse
     */
    public function postCancelInquireByOrganization($inquireId, User $organization)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];

        return new RestResponse($repo->cancelInquireByOrganization(Inquire::findOrFail($inquireId), $organization));
    }

    /**
     * @param User $candidate
     *
     * @return RestResponse
     */
    public function getJobs(User $candidate)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];

        return new RestResponse($repo->jobs($candidate));
    }

    /**
     * @param User $organization
     * @return RestResponse
     */
    public function getCandidatesForOrganization(User $organization)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];
        //$repo->candidatesForOrganization($organization);


       // dd($this->getLogQueries());
        return new RestResponse($repo->candidatesForOrganization($organization));
    }

    /**
     * @param User $agency
     * @return RestResponse
     */
    public function getCandidatesForAgency(User $agency)
    {
        /** @var  $repo InquireRepository */
        $repo =  $this->repoContainer[InquireRepositoryInterface::KEY];

        return new RestResponse($repo->candidatesForAgency($agency));
    }
} 