<?php

namespace MissionNext\Controllers\Api\Affiliate;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use MissionNext\Api\Exceptions\AffiliateException;
use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\DB\SqlStatement\Sql;
use MissionNext\Models\Affiliate\Affiliate;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Role\Role;
use MissionNext\Models\User\User;
use MissionNext\Routing\Routing;

/**
 * Class AffiliateController
 *
 * @package MissionNext\Controllers\Api\Affiliate
 *
 */
class AffiliateController extends BaseController
{
    /**
     * Get affiliates <br>
     *
     * @param integer $affiliateId
     * @param string $affiliateType
     *
     * @return RestResponse
     */
    public function getAffiliates($affiliateId, $affiliateType)
    {
        $baseQuery =
            Affiliate::select("status", "affiliate_approver_type", "organization_cached_profile.data as organization_profile",
            "agency_cached_profile.data as agency_profile", "affiliate_approver", "affiliate_requester")
            ->leftJoin("organization_cached_profile", function ($join) {
                $join->on("organization_cached_profile.id", "=", "affiliate_approver")
                    ->orOn("organization_cached_profile.id", "=", "affiliate_requester");

            })
            ->leftJoin("agency_cached_profile", function ($join) {
                $join->on("agency_cached_profile.id", "=", "affiliate_approver")
                    ->orOn("agency_cached_profile.id", "=", "affiliate_requester");

            })
            ->where("app_id", "=", $this->securityContext()->getApp()->id());

        if ($affiliateType === Affiliate::TYPE_ANY) {

            $query = $baseQuery
                ->where("app_id", "=", $this->securityContext()->getApp()->id())
                ->where(function($query) use ($affiliateId){
                    $query->where("affiliate_requester", '=', $affiliateId)
                    ->orWhere("affiliate_approver", '=', $affiliateId);
                });


        } else {

            $query = $baseQuery->where("affiliate_" . $affiliateType, '=', $affiliateId);
        }

        $res = $query->get()->each(function ($el) use ($affiliateId) {
            $el->organization_profile = json_decode($el->organization_profile);
            $el->organization_profile->role = BaseDataModel::ORGANIZATION;
            $el->organization_profile->affiliate_type = $el->affiliate_approver == $el->organization_profile->id ? Affiliate::TYPE_APPROVER : Affiliate::TYPE_REQUESTER;
            $el->agency_profile = json_decode($el->agency_profile);
            $el->agency_profile->role = BaseDataModel::AGENCY;
            $el->agency_profile->affiliate_type = $el->affiliate_approver == $el->agency_profile->id ? Affiliate::TYPE_APPROVER : Affiliate::TYPE_REQUESTER;
            $el->profile = $affiliateId == $el->organization_profile->id ? "organization_profile" : "agency_profile";
        });


        return new RestResponse($res);
    }

    /**
     * Get Agency jobs <br>
     *
     * @param integer $affiliateId
     *
     * @return RestResponse
     *
     * @throws \MissionNext\Api\Exceptions\AffiliateException
     */
    public function getAgencyJobs($affiliateId)
    {
        $agency = $this->getRequester($affiliateId);
        if ($agency->roles()->first()->role !== BaseDataModel::AGENCY) {

            throw new AffiliateException("Only agencies can view jobs");
        }


        $res = Affiliate::select(
            "job_cached_profile.data as job_data",
            "organization_cached_profile.data as org_data",
            "organization_cached_profile.id",
            "affiliates.status as status"
        )

            ->join("job_cached_profile", function($join){
               $join->on(DB::raw("(job_cached_profile.data->>'organization_id')::int"), "=", "affiliate_approver" )
                    ->orOn(DB::raw("(job_cached_profile.data->>'organization_id')::int"), "=", "affiliate_requester");

            })
            ->where("status", "=", Affiliate::STATUS_APPROVED)
            ->where("app_id","=", $this->securityContext()->getApp()->id())
            ->whereRaw("job_cached_profile.data->>'app_id' = ? ", [$this->securityContext()->getApp()->id()])
            ->join("organization_cached_profile", DB::raw("(job_cached_profile.data->>'organization_id')::int"), "=", "organization_cached_profile.id")
            ->where(function($query) use ($affiliateId){
                $query->where("affiliate_requester", "=", $affiliateId)
                      ->orWhere("affiliate_approver", "=", $affiliateId);
            })
            ->get();


        $data = [];
        foreach($res as $r){
           if (!isset($data[$r->id])) {

                $data[$r->id] = ["org_data" => json_decode($r->org_data), "jobs" => [] ];
            }
            array_push($data[$r->id]["jobs"], json_decode($r->job_data) );
        }


        return new RestResponse(array_values($data));
    }

    /**
     * Get Affiliates<br>
     *
     * @param integer $requesterId
     * @param integer $approverId
     *
     * @return RestResponse
     */
    public function getIndex($requesterId, $approverId)
    {

        return new RestResponse(Affiliate::where("affiliate_approver", '=', $approverId)
            ->where("affiliate_requester", '=', $requesterId)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())

            ->first());
    }

    /**
     * Create Affiliate
     *
     * @param integer $requesterId
     * @param  integer $approverId
     *
     * @return RestResponse
     * @throws \MissionNext\Api\Exceptions\AffiliateException
     */
    public function postIndex($requesterId, $approverId)
    {
        $affiliateData = $this->affiliateCheck($requesterId, $approverId);
        $affiliateData["status"] = Affiliate::STATUS_PENDING;

        return new RestResponse(Affiliate::create($affiliateData));
    }

    /**
     * Approve Affiliate
     *
     * @param integer $requesterId
     * @param integer $approverId
     *
     * @return RestResponse
     */
    public function postApprove($requesterId, $approverId)
    {
        $affiliate = Affiliate::where("affiliate_approver", '=', $approverId)
            ->where("affiliate_requester", '=', $requesterId)
            ->where("status", '<>', Affiliate::STATUS_APPROVED)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())
            ->firstOrFail();

        $affiliate->status = Affiliate::STATUS_APPROVED;
        $affiliate->save();

        return new RestResponse($affiliate);
    }

    /**
     * Cancel Affiliate
     *
     * @param $requesterId
     * @param $approverId
     *
     * @return RestResponse
     */
    public function postCancel($requesterId, $approverId)
    {
        $affiliate = Affiliate::where("affiliate_approver", '=', $approverId)
            ->where("affiliate_requester", '=', $requesterId)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())
            ->delete();

        return new RestResponse($affiliate);
    }

    /**
     * Pend Affiliate
     *
     * @param $requesterId
     * @param $approverId
     *
     * @return RestResponse
     */
    public function postPend($requesterId, $approverId)
    {
        $affiliate = Affiliate::where("affiliate_approver", '=', $approverId)
            ->where("affiliate_requester", '=', $requesterId)
            ->where("status", '<>', Affiliate::STATUS_PENDING)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())
            ->firstOrFail();

        $affiliate->status = Affiliate::STATUS_PENDING;
        $affiliate->save();

        return new RestResponse($affiliate);
    }

    /**
     * @param $requesterId
     * @param $approverId
     *
     * @return array
     *
     * @throws \MissionNext\Api\Exceptions\AffiliateException
     */
    private function affiliateCheck($requesterId, $approverId)
    {
        if ($requesterId == $approverId) {

            throw new AffiliateException("Requester and Approver are same users", AffiliateException::ON_REQUEST);
        }
        $affiliate = Affiliate::where("affiliate_approver", '=', $requesterId)
            ->where("affiliate_requester", '=', $approverId)
            ->where("app_id", '=', $this->securityContext()->getApp()->id())
            ->first();

        if ($affiliate) {

            throw new AffiliateException("Affiliate requester {$affiliate->affiliate_requester} is in {$affiliate->status} status to approver {$affiliate->affiliate_approver}");
        }

        $requester = $this->getRequester($requesterId);

        $approver = $this->getApprover($approverId);

        $requesterRole = $requester->roles()->first()->role;
        $approverRole = $approver->roles()->first()->role;
        $affiliateRoles = [BaseDataModel::AGENCY, BaseDataModel::ORGANIZATION];
        if (!in_array($requesterRole, $affiliateRoles) || !in_array($approverRole, $affiliateRoles)) {

            throw new AffiliateException("Affiliate users must have one of the roles " . implode(", ", $affiliateRoles), AffiliateException::ON_REQUEST);
        }

        if ($requesterRole === $approverRole) {

            throw new AffiliateException("Affiliate users can't have same roles '$requesterRole , $approverRole' ", AffiliateException::ON_REQUEST);
        }

        return [
            "affiliate_approver" => $approverId,
            "affiliate_requester" => $requesterId,
            "affiliate_approver_type" => $approverRole,
            "app_id" => $this->securityContext()->getApp()->id()
        ];
    }

    /**
     * @param $requesterId
     *
     * @return User
     */
    private function getRequester($requesterId)
    {

        return $this->userRepo()->find($requesterId);
    }

    /**
     * @param $approverId
     *
     * @return User
     */
    private function getApprover($approverId)
    {

        return $this->userRepo()->find($approverId);
    }

    /**
     * @param $affiliateId
     *
     * @return Role
     */
    private function getRole($affiliateId)
    {

        return $this->userRepo()->find($affiliateId)->roles()->first();
    }
}