<?php


namespace MissionNext\Core\Security;

use MissionNext\Api\Auth\Token;

abstract class AbstractContext
{

    /**
     * @return $this
     */
    public function getInstance()
    {

        return $this;
    }
    /**
     * @var Token
     */
    protected $token;


    /**
     * @return AbstractToken
     */
    public function getToken()
    {

        return $this->token;
    }

    /**
     * @param AbstractToken $token
     *
     * @return $this
     */
    public function setToken(AbstractToken $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return \MissionNext\Models\Application\Application
     */
    abstract  public function getApp();

} 