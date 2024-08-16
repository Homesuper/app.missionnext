<?php

namespace MissionNext\Controllers\Api\Folder;


use MissionNext\Api\Exceptions\SecurityContextException;
use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Filter\RouteSecurityFilter;
use MissionNext\Models\Folder\Folder;
use MissionNext\Models\Translation\FolderTrans;

/**
 * Class FolderController
 *
 * @package MissionNext\Controllers\Api\Folder
 */
class FolderController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function index()
    {

        return new RestResponse(Folder::where("app_id", "=", $this->securityContext()->getApp()->id())->get());
    }

    /**
     * @return RestResponse
     *
     * @throws \MissionNext\Api\Exceptions\SecurityContextException
     */
    public function store()
    {
        if (!RouteSecurityFilter::isAllowedRole($this->request->request->get('role'))){

            throw new SecurityContextException("role doesn't exists");
        }

        $data = $this->request->request->all();
        $data["app_id"] = $this->securityContext()->getApp()->id();
        return new RestResponse(Folder::create($data));
    }

    /**
     * @param integer $id
     *
     * @return RestResponse
     */
    public function show($id)
    {

        return new RestResponse( Folder::where("app_id", "=", $this->securityContext()->getApp()->id())
                                         ->where("id", "=", $id)
                                         ->firstOrFail()
        );
    }

    /**
     * @param integer $id
     *
     * @return RestResponse
     */
    public function update($id)
    {
       $folder =  Folder::where("app_id", "=", $this->securityContext()->getApp()->id())
           ->where("id", "=", $id)
           ->firstOrFail();
       $folder->title = $this->request->request->get('title');
       $folder->save();

       return new RestResponse($folder);
    }

    /**
     * @param integer $id
     *
     * @return RestResponse
     */
    public function destroy($id)
    {
        $folder = Folder::where("app_id", "=", $this->securityContext()->getApp()->id())
        ->where("id", "=", $id)
        ->firstOrFail();

        $folder->delete();

        return new RestResponse($folder);
    }

    /**
     * @param string $role
     *
     * @return RestResponse
     */
    public function role($role)
    {
        $folders = Folder::where("role","=",$role)
                ->where("app_id", "=", $this->securityContext()->getApp()->id())
                ->where("user_id", "=", null)
                ->get();

        $foldersIds = $folders->lists('id') ? : [0];

        $foldersTrans = (new FolderTrans())->queryFolderTrans($this->securityContext())->whereIn('folder_id', $foldersIds)->get();

        $folders->each(function($f) use ($foldersTrans) {
            $f->value = null;
            foreach($foldersTrans as $ft){
                if ($f->id == $ft->folder_id){
                    $f->value = $ft->value;
                }
            }
        });

        return new RestResponse($folders);
    }

    public function roleWithUser($role, $userId)
    {
        $custom_folders = Folder::where("role","=",$role)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())
            ->where(function($query) use ($userId) {
                $query->where("user_id", "=", $userId);
            })
            ->orderBy("id", "asc")
            ->get();

        $foldersIds = $custom_folders->lists('id') ? : [0];

        $foldersTrans = (new FolderTrans())->queryFolderTrans($this->securityContext())->whereIn('folder_id', $foldersIds)->get();

        $custom_folders->each(function($f) use ($foldersTrans) {
            $f->value = null;
            foreach($foldersTrans as $ft){
                if ($f->id == $ft->folder_id){
                    $f->value = $ft->value;
                }
            }
        });

        $default_folders = Folder::where("role","=",$role)
            ->where("app_id", "=", $this->securityContext()->getApp()->id())
            ->where(function($query) use ($userId) {
                $query->where("user_id", "=", null);
            })
            ->orderBy("id", "asc")
            ->get();

        $foldersIds = $default_folders->lists('id') ? : [0];

        $foldersTrans = (new FolderTrans())->queryFolderTrans($this->securityContext())->whereIn('folder_id', $foldersIds)->get();

        $default_folders->each(function($f) use ($foldersTrans) {
            $f->value = null;
            foreach($foldersTrans as $ft){
                if ($f->id == $ft->folder_id){
                    $f->value = $ft->value;
                }
            }
        });

        if ($custom_folders->count() > 0) {
            $folders = $custom_folders->merge($default_folders);
        } else {
            $folders = $default_folders;
        }

        return new RestResponse($folders);
    }

} 