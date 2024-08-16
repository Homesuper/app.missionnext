<?php

namespace MissionNext\Facade;


use Illuminate\Support\Facades\Facade;

class SecurityContext extends Facade
{

    protected static function getFacadeAccessor()
    {

        return 'security_context';
    }

} 