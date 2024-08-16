<?php

/**
 * Class UserControllerTest
 * @see MissionNext\Controllers\Api\Profile\UserController
 */
class UserControllerTest extends TestCase
{
    /**
     * @see MissionNext\Controllers\Api\Profile\UserController::update()
     */
    public function testUpdate()
    {
        $file = new \Symfony\Component\HttpFoundation\File\UploadedFile(public_path()."/some.txt",  "somesdf.pdf" );

        $response = $this->authorizedCall('PUT', 'profile/4', ["birth_date" => "1900-11-11"], [],
            ["resume" => $file]);
        $responseData = $response->getData();
        dd($responseData);


        $this->assertTrue((bool)$responseData->status);
    }
} 