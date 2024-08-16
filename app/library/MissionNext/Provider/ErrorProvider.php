<?php

namespace MissionNext\Provider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;
use MissionNext\Api\Exceptions\AuthorizeException;
use MissionNext\Api\Exceptions\BadDataException;
use MissionNext\Api\Exceptions\ValidationException;
use MissionNext\Api\Response\RestResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorProvider extends ServiceProvider
{

    public function register()
    {
        App::error(function (Exception $exception, $code) {
            Log::error($exception);

            if (App::environment('local')) {
                dd(get_class($exception), "message = ".$exception->getMessage());
            }

            return (new RestResponse())->setErrorData($exception);
        });

        App::error(function (ValidationException $exception, $code) {

            return new RestResponse($exception);
        });

        App::error(function (BadDataException $exception, $code) {

            return new RestResponse($exception);
        });

        App::error(function (AuthorizeException $exception, $code) {

            return new RestResponse($exception);
        });

    }
}
