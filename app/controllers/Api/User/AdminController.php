<?php


namespace MissionNext\Controllers\Api\User;


use MissionNext\Api\Response\RestResponse;
use MissionNext\Controllers\Api\BaseController;
use MissionNext\Models\Admin\AdminUserModel;

/**
 * Class AdminController
 * @package MissionNext\Controllers\Api\User
 */
class AdminController extends BaseController
{
    /**
     * @return RestResponse
     */
    public function getEmails()
    {

        return new RestResponse(AdminUserModel::all()->lists('email'));
    }
} 