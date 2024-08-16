<?php
namespace MissionNext\Api\Exceptions;

use Exception;

class ResponseDataException extends Exception
{


    public function __construct($message, $code = 0, Exception $previous = null)
    {
        if (is_object($message)) {
            parent::__construct("Unexpected response data object " . get_class($message));
        } else {
            parent::__construct($message, $code, $previous);
        }
    }


} 