<?php


namespace MissionNext\Models\Translation;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Models\ModelInterface;

class FolderTrans extends Model implements ModelInterface
{

    protected $table = 'folder_trans';

    public $timestamps = false;

    protected $fillable = array('lang_id', 'app_id', 'folder_id', 'value');

    /**
     * @param \ArrayObject $transObject
     *
     * @return boolean
     */
    public function insertTransData(\ArrayObject $transObject)
    {

        return $this->insert(
            [
                'folder_id' => $transObject->folder_id,
                'app_id' => $transObject->app_id,
                'value' => $transObject->value,
                'lang_id' => $transObject->lang_id,

            ]
        );
    }

    /**
     * @param \ArrayObject $transObject
     *
     * @return boolean
     */
    public function updateTransData(\ArrayObject $transObject)
    {

        return $this->whereAppId($transObject->app_id)
            ->whereFolderId($transObject->folder_id)
            ->whereLangId($transObject->lang_id)
            ->update(['value' => $transObject->value]);
    }

    /**
     * @param $role
     * @param SecurityContext $securityContext
     *
     * @return Collection
     */
    public function folderTrans(SecurityContext $securityContext, $role)
    {

      return  $this->leftJoin('folders', 'folders.id','=', 'folder_trans.folder_id')
             ->where('folders.role', '=', $role)
             ->where('folder_trans.app_id', '=', $securityContext->getApp()->id())
             ->select('folder_trans.lang_id', 'folder_trans.app_id', 'folder_trans.value', 'folders.role',
                    'folder_trans.folder_id', 'folders.title')
             ->get();
    }

    /**
     * @param SecurityContext $securityContext
     * @return Builder
     */
    public function queryFolderTrans(SecurityContext $securityContext)
    {
        return $this->whereAppId($securityContext->getApp()->id())
                    ->whereLangId($securityContext->getToken()->language()->id);

    }


} 