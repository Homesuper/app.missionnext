<?php
namespace MissionNext\Api\Exceptions;


use Illuminate\Support\MessageBag;

class ValidationException extends \Exception
{
    /** @var  MessageBag */
    private $messages;

    /**
     * @param MessageBag $messages
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(MessageBag $messages, $code = 0, \Exception $previous = null)
    {
        $this->messages = $messages;
        $this->code = $code;
    }

    /**
     * @return MessageBag
     */
    public function getErrorBag()
    {

        return $this->messages;
    }

} 