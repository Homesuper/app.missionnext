<?php

namespace MissionNext\Repos\Inquire;


use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MissionNext\Api\Auth\ISecurityContextAware;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Api\Service\DataTransformers\UserCachedDataStrategy;
use MissionNext\Api\Service\DataTransformers\UserCachedTransformer;
use MissionNext\Models\Affiliate\Affiliate;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Inquire\Inquire;
use MissionNext\Models\Job\Job;
use MissionNext\Models\User\User;
use MissionNext\Repos\AbstractRepository;
use MissionNext\Repos\Affiliate\AffiliateRepository;
use MissionNext\Repos\Affiliate\AffiliateRepositoryInterface;
use MissionNext\Repos\CachedData\UserCachedRepository;
use MissionNext\Repos\CachedData\UserCachedRepositoryInterface;
use MissionNext\Repos\User\JobRepository;
use MissionNext\Repos\User\JobRepositoryInterface;
use MissionNext\Repos\User\UserRepository;
use MissionNext\Repos\User\UserRepositoryInterface;

class InquireRepository extends AbstractRepository implements ISecurityContextAware, InquireRepositoryInterface
{
    protected $modelClassName = Inquire::class;
    /** @var  SecurityContext */
    public  $securityContext;

    /**
     * @return Inquire
     */
    public function getModel()
    {

        return $this->model;
    }

    /**
     * @param SecurityContext $securityContext
     *
     * @return $this
     */
    public function setSecurityContext(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;

        return $this;
    }

    /**
     * @param User $user
     * @param Job $job
     * @param $status
     * @return Builder
     */
    public function checkInquire(User $user, Job $job, $status = null)
    {
        $query =  $this->where("app_id", "=", $this->repoContainer->securityContext()->getApp()->id())
            ->where("candidate_id", "=", $user->id)
            ->where("job_id", "=", $job->id);

        return $status ?  $query->where("status", "=", $status) : $query;
    }

    /**
     * @param User $user
     * @param Job $job
     *
     * @return Inquire
     */
    public function inquire(User $user, Job $job)
    {

         return  $this->checkInquire($user, $job, Inquire::STATUS_INQUIRED)->first() ? :  $this->create([
             "app_id" => $this->repoContainer->securityContext()->getApp()->id(),
             "candidate_id" => $user->id,
             "job_id" => $job->id,
             "status" => Inquire::STATUS_INQUIRED,
         ]);
    }

    /**
     * @param User $user
     * @param Job $job
     *
     * @return Inquire
     */
    public function cancelJobByCandidate(Job $job, User $user)
    {

        return  $this->checkInquire($user, $job)->delete();
    }

    /**
     * @param Inquire $inquire
     * @param User $agency
     *
     * @return mixed
     */
    public function cancelInquireByAgency(Inquire $inquire, User $agency)
    {
        $orgIds = $this->orgsIdsbyAgency($agency);

        $jobIds = $this->jobsByOrganization($orgIds);

        if (in_array($inquire->job_id, $jobIds)) {
            $inquireEntity = $this->getModel()
                ->where("id", $inquire->id)
                ->where("app_id", "=", $this->repoContainer->securityContext()->getApp()->id() )->first();
            $inquireEntity->status = Inquire::STATUS_DELETED;
            $inquireEntity->save();

            return $inquireEntity;
        }

        return false;
    }

    /**
     * @param Inquire $inquire
     * @param User $org
     *
     * @return mixed
     */
    public function cancelInquireByOrganization(Inquire $inquire, User $org)
    {
        $jobIds = $this->jobsByOrganization([$org->id]);

        if (in_array($inquire->job_id, $jobIds)) {
            $inquireEntity = $this->getModel()
                ->where("id", $inquire->id)
                ->where("app_id", "=", $this->repoContainer->securityContext()->getApp()->id() )->first();
            $inquireEntity->status = Inquire::STATUS_DELETED;
            $inquireEntity->save();

            return $inquireEntity;
        }

        return false;
    }
    /**
     * @param array $orgIds
     *
     * @return Collection|array
     */
    private function jobsByOrganization(array $orgIds = null)
    {
        /** @var  $jobRepo JobRepository */
        $jobRepo = $this->repoContainer[JobRepositoryInterface::KEY];

        return $orgIds ? $jobRepo->getModel()
            ->whereIn("organization_id",  $orgIds)
            ->where("app_id", "=", $this->repoContainer->securityContext()->getApp()->id())
            ->get()->lists('id')
             : [];
    }

    /**
     * @param User $user
     * @return Collection
     */
    public function jobs(User $user)
    {
       /** @var  $userCacheRepo UserCachedRepository */
       $userCacheRepo = $this->repoContainer[UserCachedRepositoryInterface::KEY]->dataOfType(BaseDataModel::JOB);

       $builder =  $userCacheRepo->getModel()
                ->leftJoin("inquires", "inquires.job_id",'=', "job_cached_profile.id")
                ->leftJoin('organization_cached_profile', 'organization_cached_profile.id','=',DB::raw("(job_cached_profile.data->>'organization_id')::int"))
                ->where("inquires.candidate_id","=", $user->id)
                ->where(DB::raw("job_cached_profile.data->>'app_id'"), "=", $this->repoContainer->securityContext()->getApp()->id())
                ->where("inquires.app_id","=", $this->repoContainer->securityContext()->getApp()->id())
                ->select("job_cached_profile.data", "inquires.status", 'organization_cached_profile.data as organization');
               //->get();
          // dd(DB::getQueryLog());
//        dd($builder->toArray());

        return
            (new UserCachedTransformer($builder, new UserCachedDataStrategy( [ ['data' => true], 'organization'  ] )))->get();
    }

    /**
     * @param array $jobIds
     *
     * @return Collection
     */
    private function candidateByJobs(array $jobIds)
    {

        $builder =   $this->getModel()
            ->leftJoin("candidate_cached_profile", "candidate_cached_profile.id", "=", "inquires.candidate_id")
            ->leftJoin("job_cached_profile", "job_cached_profile.id", "=", "inquires.job_id")
            ->whereIn("inquires.job_id", $jobIds)
            ->where("inquires.app_id", "=", $this->repoContainer->securityContext()->getApp()->id() )
            ->where("inquires.status", "=", Inquire::STATUS_INQUIRED)
            ->select(DB::raw("candidate_cached_profile.id, candidate_cached_profile.data as candidate, job_cached_profile.data as job, inquires.id as id, inquires.updated_at as updated_at") );
//distinct on (candidate_cached_profile.id)
        return
            (new UserCachedTransformer($builder, new UserCachedDataStrategy( [ 'candidate', ['job'=>false] ] )))->get();
         // job => false = job as key , true =
    }

    /**
     * @param User $user
     *
     * @return Collection|array
     */
    public function candidatesForOrganization(User $user)
    {
        $jobIds =  $this->jobsByOrganization([$user->id]);

        return  $jobIds ?
                  $this->candidateByJobs($jobIds)
               : [];
    }

    /**
     * @param User $user
     *
     * @return Collection|array
     */
    public function candidatesForAgency(User $user)
    {
        $orgIds = $this->orgsIdsbyAgency($user);

        $jobIds = $this->jobsByOrganization($orgIds);

        return  $jobIds ?
             $this->candidateByJobs($jobIds)
            : [];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    private function orgsIdsbyAgency(User $user)
    {
        /** @var  $affilRepo AffiliateRepository */
        $affilRepo = $this->repoContainer[AffiliateRepositoryInterface::KEY];

        $orgIdsR = $affilRepo->where("affiliate_approver","=", $user->id)
            ->where("status","=", Affiliate::STATUS_APPROVED)
            ->where("app_id", "=", $this->repoContainer->securityContext()->getApp()->id())
            ->get()
            ->lists("affiliate_requester");

        $orgIdsA = $affilRepo->where("affiliate_requester","=", $user->id)
            ->where("app_id", "=", $this->repoContainer->securityContext()->getApp()->id())
            ->where("status","=", Affiliate::STATUS_APPROVED)
            ->get()
            ->lists("affiliate_approver");

        return array_merge($orgIdsA, $orgIdsR);
    }

}