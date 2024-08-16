<?php


namespace MissionNext\Controllers\traits;

use Illuminate\Support\Facades\DB;
use MissionNext\Api\Auth\Token;
use MissionNext\Facade\SecurityContext as FSecurityContext;
use MissionNext\Api\Auth\SecurityContext;
use MissionNext\Models\Application\Application;

trait Controller
{
    /**
     * @return SecurityContext
     */
    protected function securityContext()
    {

        return FSecurityContext::getInstance();
    }

    /**
     * @return Token
     */
    protected function getToken()
    {

        return $this->securityContext()->getToken();
    }

    /**
     * @return \MissionNext\Models\User\User
     */
    protected function getUser()
    {

        return $this->getToken()->currentUser();
    }

    /**
     * @return Application
     */
    protected function getApp()
    {

        return $this->getToken()->getApp();
    }

    /**
     * @return  []
     */
    protected function getLogQueries()
    {

        return DB::getQueryLog();
    }
} 