<?php

namespace MissionNext\Controllers\Api\Meta;

use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Affiliate\Affiliate;
use MissionNext\Models\Favorite\Favorite;
use MissionNext\Models\FolderApps\FolderApps;
use MissionNext\Models\Notes\Notes;

class MetaController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getMetaForAgency($user_id, $role)
	{
        $affiliates = $this->getAffiliates($user_id, Affiliate::TYPE_ANY);

        $ids = [];
        foreach ($affiliates as $item) {
            $ids[] = $item->org_id;
        }

        $own_notes = $notes = $favorites = $folders = $affiliatesOrg = [];

        $own_notes = Notes::select("user_id", "for_user_id as note_owner", "notes")
            ->where('for_user_id', $user_id)
            ->where('user_type', $role)
            ->get()->toArray();

        if (count($ids) > 0) {
            $notes = Notes::select("user_id", "for_user_id as note_owner", "notes")
                ->whereIn('for_user_id', $ids)
                ->where('user_type', $role)
                ->get()->toArray();

            $favorites = Favorite::select("user_id as favorite_owner", "target_id")
                ->whereIn("user_id", $ids)
                ->where("target_type", $role)
                ->where("app_id", $this->securityContext()->getApp()->id())
                ->get()->toArray();

            $folders = FolderApps::select("user_id", "for_user_id as folder_owner", "folder")
                ->whereIn("for_user_id", $ids)
                ->where("user_type", $role)
                ->where("app_id", $this->securityContext()->getApp()->id())
                ->get()->toArray();
        }

        foreach ($affiliates as $item) {
            $affiliatesOrg[$item["organization_profile"]->id] = [
                'id'    => $item["organization_profile"]->id,
                'name'  => $item["organization_profile"]->profileData->organization_name
            ];
        }

        return new RestResponse([
            'own_notes'      => $own_notes,
            'notes'         => $notes,
            'favorites'     => $favorites,
            'folders'       => $folders,
            'affiliates'    => $affiliatesOrg
        ]);
	}

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
            $el->org_id = $el->organization_profile->id;
        });

        return $res;
    }
}
