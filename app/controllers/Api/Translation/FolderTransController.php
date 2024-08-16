<?php


namespace MissionNext\Controllers\Api\Translation;

use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Translation\FolderTrans;

/**
 * Class FolderTransController
 * @package MissionNext\Controllers\Api\Translation
 */
class FolderTransController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function postIndex()
    {
        $data = $this->request->request->all();
        foreach($data['folder'] as $transGroup)
        {
            $dataTrans = new \ArrayObject($transGroup, \ArrayObject::ARRAY_AS_PROPS);
            $dataTrans->offsetSet('app_id', $this->getApp()->id());
            /** @var  $folderTrans FolderTrans */
            $folderTrans =  FolderTrans::whereLangId($dataTrans->lang_id)
                                        ->whereAppId($dataTrans->app_id)
                                        ->whereFolderId($dataTrans->folder_id)
                                        ->first() ? : new FolderTrans();

            $folderTrans->app_id  ?   $folderTrans->updateTransData($dataTrans)
                :   $folderTrans->insertTransData($dataTrans);
        }

        return new RestResponse([$data]);
    }


    public function getFolderTrans($type)
    {

        return new RestResponse((new FolderTrans())->folderTrans($this->securityContext(), $type) );
    }
} 