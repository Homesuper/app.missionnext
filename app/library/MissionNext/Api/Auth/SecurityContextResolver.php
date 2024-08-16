<?php


namespace MissionNext\Api\Auth;

use MissionNext\Facade\SecurityContext as FS;

class SecurityContextResolver extends ObjectResolver
{

    /**
     * @param ISecurityContextAware $class
     * @return mixed
     */
    public function __construct(ISecurityContextAware $class)
    {
        $class->setSecurityContext(FS::getInstance());
        $this->resolvedObject = $class;
    }

} 