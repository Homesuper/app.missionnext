<?php


namespace MissionNext\Api\Auth;


interface ISecurityContextAware extends IObjectAware
{

    public function setSecurityContext(SecurityContext $securityContext);

} 