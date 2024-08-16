<?php

namespace MissionNext\Api\Response;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Contracts\ArrayableInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use MissionNext\Api\Exceptions\AuthorizeException;
use MissionNext\Api\Exceptions\BadDataException;
use MissionNext\Api\Exceptions\ResponseDataException;
use Illuminate\Database\Eloquent\Model;
use MissionNext\Api\Exceptions\ValidationException;

class RestResponse extends JsonResponse
{
    /**
     * @param array $data
     *
     * @return RestData
     *
     * @throws \MissionNext\Api\Exceptions\ResponseDataException
     */
    protected function getResponseData($data = [])
    {
        if ($data instanceof Relation) {

            throw new ResponseDataException($data);
        }

        $rawData = $data;
        $status = RestData::SUCCESS;
        if ($data instanceof Collection) {
            $rawData =  $data->toArray();
        } elseif ($data instanceof Model) {
            $rawData = $data->toArray();
        } elseif ($data instanceof ValidationException){
            $rawData = $data->getErrorBag()->getMessages();
            $status = RestData::VALIDATION_ERROR;
        } elseif ($data instanceof BadDataException){
            $rawData = $data->getMessage();
            $status = RestData::BAD_DATA_ERROR;
        } elseif ($data instanceof AuthorizeException){
            $rawData = $data->getMessage();
            $status = RestData::VALIDATION_ERROR;
        }

        return RestData::setData($rawData, $status);
    }

    /**
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|void
     */
    public function setData($data = [])
    {
        parent::setData($this->getResponseData($data));
    }

    /**
     * @param \Exception $e
     *
     * @return $this
     */
    public function setErrorData(\Exception $e)
    {
        $responseData = RestData::setData(["error" =>
            ["message" => $e->getMessage(), "code" => $e->getCode(), "type" => (new \ReflectionObject($e))->getShortName()]
        ], 0);
        parent::setData($responseData);

        return $this;
    }

} 