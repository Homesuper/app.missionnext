<?php

namespace MissionNext\Models\Folder;


use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Models\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\Observers\FolderObserver;

class Folder extends Model implements ModelInterface
{
    protected $table = "folders";

    protected $fillable = ["title", "role", "app_id", "user_id"];

    protected static function boot()
    {
        parent::boot();
        parent::observe(new FolderObserver);
    }

    public function folderWithTrans(SecurityContext $securityContext, $role)
    {
        return  $this->leftJoin('folder_trans', function($join) use ($role, $securityContext){
            $join->on('folders.id','=', 'folder_trans.folder_id')
                 ->where('folders.role', '=', $role)
                ->where('folder_trans.app_id', '=', $securityContext->getApp()->id())
                ->where('folder_trans.lang_id', '=', $securityContext->getToken()->language()->id);
             })
            ->distinct()

            ->select('folder_trans.lang_id', 'folder_trans.app_id', 'folder_trans.value', 'folders.role',
                'folder_trans.folder_id', 'folders.title')
            ->get();

    }
} 