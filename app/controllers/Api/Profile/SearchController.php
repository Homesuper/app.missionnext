<?php

namespace MissionNext\Controllers\Api\Profile;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Request;
use MissionNext\Api\Response\RestResponse;
use MissionNext\Api\Service\Matching\TransData;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Api\Exceptions\SearchProfileException;
use MissionNext\Facade\SecurityContext;
use MissionNext\Models\DataModel\AppDataModel;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Favorite\Favorite;
use MissionNext\Models\Field\Candidate;
use MissionNext\Models\Field\FieldType;
use MissionNext\Models\FolderApps\FolderApps;
use MissionNext\Models\Notes\Notes;
use MissionNext\Models\SearchData\SearchData;
use MissionNext\Models\Subscription\Subscription;
use MissionNext\Models\User\User;
use MissionNext\Repos\Field\Field;

/**
 * Class SearchController
 * @package MissionNext\Controllers\Api\Profile
 */
class SearchController extends BaseController
{
    /**
     * @param $searchType
     * @param $userType
     * @param $userId
     * @return RestResponse
     * @throws \MissionNext\Api\Exceptions\SearchProfileException
     * @throws \MissionNext\Api\Exceptions\SecurityContextException
     */
    public function postIndex($searchType, $userType, $userId)
    {
        SecurityContext::getInstance()->getToken()->setRoles([$searchType]);
        $page = $this->request->get('page');
        $offset = ($page - 1) * 500;
        $profileSearch = $this->request->get("params")['profileData'];
        $userSearch = $this->request->get("params");
        unset($userSearch['profileData']);
        unset($userSearch['timestamp']);

        $bindings = [];
        $tableName = $searchType.'_cached_profile';

        $folderNotesTable = (new Notes)->getTable();
        $folderAppsTable = (new FolderApps)->getTable();
        $favoriteTable = (new Favorite)->getTable();

        $query = "SELECT n.notes, cp.data, fA.folder as folderName, f.id as favorite, ocp.data->'profileData'->>'organization_name' as org_name FROM {$tableName} as cp
                  LEFT JOIN {$folderNotesTable} n ON cp.id = n.user_id
                              AND n.for_user_id = ? AND n.user_type = ?
                  LEFT JOIN {$folderAppsTable} fA ON cp.id = fA.user_id
                   ";

        $query .= "AND fA.for_user_id = ? AND fA.user_type = ? AND fA.app_id = ?
              LEFT JOIN {$favoriteTable} f ON cp.id = f.target_id
                       AND f.user_id = ? AND f.target_type = ? AND f.app_id = ?";

        $query .= "
            LEFT JOIN organization_cached_profile ocp ON ocp.id=(cp.data->>'organization_id')::int
            ";

        $bindings[] = $userId;
        $bindings[] = $searchType;
        $bindings[] = $userId;
        $bindings[] = $searchType;

        $bindings[] = $this->securityContext()->getApp()->id();
        $bindings[] = $userId;
        $bindings[] = $searchType;
        $bindings[] = $this->securityContext()->getApp()->id();

        $where = " WHERE ( ";

        if (!empty($profileSearch)) {

            $expandedFields = $this->viewFieldRepo()->getModel()->whereRaw(
                "CAST(meta->'search_options'->>'is_expanded' AS BOOLEAN) = true"
            )->get()->toArray();

            $currentField =  Field::currentFieldModelName(SecurityContext::getInstance());

            $inputFields =
                array_fetch(
                    (new $currentField)->where("type","=",FieldType::INPUT)->orWhere("type","=",FieldType::TEXT)->get()->toArray(),
                    "symbol_key"
                );


            if (count($expandedFields)) {
                $expandedFields = array_fetch($expandedFields, 'symbol_key');
            }

            foreach ($profileSearch as $fieldName => $value) {

                if (is_array($value)) {
                    if (in_array($fieldName, $expandedFields)) {
                        $prepend = '';
                        if (count($value) > 1){
                            $prepend = " ( ";
                        }
                        foreach ($value as $val) {
                            $query .= $where . $prepend . " ? = cp.data->'profileData'->>'{$fieldName}' ";
                            $bindings[] = $val;
                            $where = " OR ";
                            $prepend = "";
                        }
                        if (count($value) > 1){
                            $query .= " ) ";
                        }

                    } else {
                        $query .= $where . " ? && json_array_text(cp.data->'profileData'->'{$fieldName}') ";
                        $value = array_map(function($item) {
                            $item = strtolower($item);
                            if (strpos($item, "'") !== false) {
                                $item = str_replace("\\", "", $item);
                            }
                            if (strpos($item,'/') !== false) {
                                $item = str_replace("/", "\\/", $item);
                            }
                            /*if (strpos($item, '\"') !== false) {
                                $item = str_replace('"', '\\\"', $item);
                            }*/
                            return "\"$item\"";
                        }, $value);
                        $bindings[] = str_replace(["[", "]"], ["{", "}"], json_encode($value));
                    }

                } else {
                    if (in_array($fieldName,$inputFields)){
                        $query .= $where . " cp.data->'profileData'->>'{$fieldName}' ILIKE ?  ";
                        $bindings[] = '%'.$value.'%';
                    }else {
                        $query .= $where . " LOWER(?) = LOWER(cp.data->'profileData'->>'{$fieldName}') ";
                        $bindings[] = $value;
                    }
                }

                $where = " AND ";
            }
        }

        if (!empty($userSearch)) {

            foreach ($userSearch as $fN => $val) {
                $query .= $where . "  cp.data->>'{$fN}'  ILIKE ? ";
                $bindings[] = '%'.$val.'%';
            }
        }
        if (!empty($profileSearch) || !empty($userSearch)) {
            $query .= " AND ARRAY[?] <@ json_array_text(cp.data->'app_ids') ";
            $bindings[] = $this->securityContext()->getApp()->id();
        }else{

            throw new SearchProfileException("No search params specified");
        }

        if (AppDataModel::JOB == $searchType) {
            $date_limit = new \DateTime('now');
            $date_limit->modify("-6 months");
            $timelimit = $date_limit->getTimestamp();

            $query .= 'AND '.DB::raw("(cp.data->>'updated_at')::date".' >= \''.date('Y-m-d', $timelimit)).'\'';
        }
        $countQuerySelect = $query." )";

        if (AppDataModel::CANDIDATE == $searchType) {
            $countQuerySelect .= " ORDER BY (cp.data->>'last_login')::date DESC";
        }

        $searchResult = DB::select($countQuerySelect, $bindings);
        $resultList = array_slice($searchResult, $offset, 500);
        $result = [];
        foreach ($resultList as $resultItem) {
            $data           = json_decode($resultItem->data);
            $target_id = (BaseDataModel::JOB == $data->role) ? $data->organization_id : $data->id;
            $user = User::find($target_id);
            $subscription = $user->subscriptions()
                    ->where('app_id', $this->securityContext()->getApp()->id())
                    ->where('status', '<>', Subscription::STATUS_CLOSED)
                    ->where('status', '<>', Subscription::STATUS_EXPIRED)
                    ->first();

            if ($user->isActiveInApp($this->securityContext()->getApp()) && $subscription) {
                $data->notes    = $resultItem->notes;
                $data->folder   = $resultItem->foldername;
                $data->favorite = $resultItem->favorite;
                $data->org_name = $resultItem->org_name;
                $result[] = new \ArrayObject($data);
            }
        }

        $rowsCount = count($searchResult);
        $pages = ceil($rowsCount / 500);

        return new RestResponse( [
            'results' => (new TransData($this->getToken()->language(), $searchType, $result))->get(),
            'count' => $pages
        ]);

    }

    /**
     * @param $searchType
     * @param $userType
     * @param $userId
     *
     * @return RestResponse
     */
    public function putIndex($searchType, $userType, $userId )
    {
        $searchData = $this->request->request->get("search_data");
        $searchName = $this->request->request->get("search_name");
        $search = SearchData::create([
            "search_name"=> $searchName,
            "search_type"=>$searchType,
            "user_type"=>$userType,
            "user_id"=>$userId,
            "app_id" => $this->securityContext()->getApp()->id(),
            "data" => json_encode($searchData),
        ]);


        return new RestResponse($search);

    }

    /**
     * @param $searchType
     * @param $userType
     * @param $userId
     *
     * @return RestResponse
     */
    public function getIndex($searchType, $userType, $userId )
    {
        $data = SearchData::findData($searchType, $userType, $userId)->get();

        $data->each(function($d){
            $d->data = json_decode($d->data);
        });

        return new RestResponse($data);
    }

    /**
     * @param $searchId
     *
     * @return RestResponse
     */
    public function delete( $searchId, $forUserId )
    {

        return new RestResponse(SearchData::where("user_id", "=", $forUserId)
            ->where("id", "=", $searchId)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())
            ->delete());
    }
}