<?php

namespace MissionNext\Models\FolderApps;


use Illuminate\Database\Eloquent\Model;
use MissionNext\Models\ModelInterface;

class FolderApps extends Model implements ModelInterface
{
    protected $table = "folder_apps";

    protected $fillable = ["user_type", "user_id", "for_user_id", "folder", "app_id"];

    /**
     * @param $folder
     *
     * @return $this
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }
} 