<?php


namespace MissionNext\Controllers\Api\Favorite;


use Illuminate\Support\Facades\DB;
use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Favorite\Favorite;
use MissionNext\Models\Folder\Folder;
use MissionNext\Models\FolderApps\FolderApps;
use MissionNext\Models\Notes\Notes;
use MissionNext\Models\User\User;

/**
 * Class Favourite Controller
 *
 * @package MissionNext\Controllers\Api\Favorite
 */
class Controller extends BaseController {

    /**
     * Get User Favourites by role and userId
     *
     * @param integer $user_id
     * @param string $role
     *
     * @return RestResponse
     */
    public function getByRole($user_id, $role){

        $cache_table = $role . "_cached_profile";
        $notesTable = (new Notes)->getTable();
        $folderTable = (new FolderApps)->getTable();
        $query = Favorite::join($cache_table, $cache_table . ".id", "=", "favorite.target_id")
                          ->select("favorite.id", "favorite.user_id", "favorite.target_type", "favorite.target_id" , "$cache_table.data as data", "$notesTable.notes as notes", "$folderTable.folder as folder");

        if ($role === BaseDataModel::JOB) {
            $query = $query->leftJoin("organization_cached_profile",
                "organization_cached_profile.id", "=", DB::raw("(job_cached_profile.data->>'organization_id')::int"))
                ->addSelect( "organization_cached_profile.data as organization" );
        }

        $data = $query->where("favorite.app_id", '=', $this->securityContext()->getApp()->id())
            ->where("favorite.user_id", '=', $user_id)
            ->where("favorite.target_type", '=', $role)

            ->leftJoin($notesTable, function($join) use ($notesTable, $user_id){
                $join->on($notesTable . ".user_id", '=', 'favorite.target_id');
                $join->on($notesTable . ".for_user_id", '=', DB::raw($user_id));
            })
            ->leftJoin($folderTable, function ($join) use ($folderTable, $user_id, $role){
                $join->on($folderTable . ".user_id", '=', 'favorite.target_id')
                    ->where($folderTable.".for_user_id", "=", DB::raw($user_id))
                    ->where($folderTable.".user_type", "=", $role)
                    ->where($folderTable.".app_id", "=", $this->securityContext()->getApp()->id());
            })
            ->get();

        foreach($data as $key => $row){
            $dataObject = json_decode($row['data']);
            $targetUserId = ($role == BaseDataModel::JOB) ? $dataObject->organization->id : $row['target_id'];

            if ($role == BaseDataModel::JOB && User::find($targetUserId)->isActiveInApp($this->securityContext()->getApp())) {
                $data[$key]['data'] = $dataObject;

                $data[$key]['data']->organization = json_decode($row['organization']);
                unset($data[$key]['organization']);
            } elseif ($role != BaseDataModel::JOB && User::find($targetUserId)->isActiveInApp($this->securityContext()->getApp())) {
                $data[$key]['data'] = json_decode($row['data']);
            } else {
                unset($data[$key]);
            }

        }

        return new RestResponse($data);
    }

    /**
     * Save Favourite
     *
     * @return RestResponse
     */
    public function store(){

        $params = $this->request->request->all();
        $params['app_id'] = $this->securityContext()->getApp()->id();

        return new RestResponse(Favorite::create($params));
    }

    /**
     * Delete Favourite
     *
     * @param integer $favorite_id
     *
     * @return RestResponse
     */
    public function delete($favorite_id){

        return new RestResponse(Favorite::where('id', '=', $favorite_id)->delete());
    }

} 