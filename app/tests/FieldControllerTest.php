<?php
use MissionNext\Models\DataModel\BaseDataModel;
use MissionNext\Models\Field\FieldType;

/**
 * Class FieldControllerTest
 * @see Api\Field\Controller;
 */
class FieldControllerTest extends TestCase
{


    /** @see Api\Field\Controller::getIndex */
   public function testCandidateGetIndex()
   {
      $candidate = BaseDataModel::CANDIDATE;

      $response =  $this->authorizedCall('GET', $candidate.'/field');
      $responseData = $response->getData();

      $this->assertGreaterThan(2, count($responseData->data->list));
      $this->assertTrue((bool)$responseData->status);
   }

    /** @see Api\Field\Controller::getIndex */
    public function testOrganizationGetIndex()
    {
        $organization = BaseDataModel::ORGANIZATION;

        $response =  $this->authorizedCall('GET', $organization.'/field');
        $responseData = $response->getData();

        $this->assertGreaterThan(2, count($responseData->data->list));
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::getIndex */
    public function testAgencyGetIndex()
    {
        $agency = BaseDataModel::AGENCY;

        $response =  $this->authorizedCall('GET', $agency.'/field');
        $responseData = $response->getData();

        $this->assertGreaterThan(2, count($responseData->data->list));
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::postIndex */
    public function testAgencyPostIndex()
    {
        $agency = BaseDataModel::AGENCY;
        $paramams = [];

        $paramams["fields"][] = [
                            "symbol_key" => "new_date",
                            "name" => "New date",
                            "type" => FieldType::DATE,
                            "default_value" => '',
                            "choices"=> '',
                           ];

        $paramams["fields"][] = [
                             "symbol_key" => "my_movies",
                             "name" => "My Movies",
                             "type" => FieldType::CHECKBOX,
                             "default_value" => "terminator,bamby",
                             "choices" => "terminator,lolo,bamby",
                           ];

        $response =  $this->authorizedCall('POST', $agency.'/field', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("new_date", $responseData->data->list[0]->symbol_key);
        $this->assertEquals("my_movies", $responseData->data->list[1]->symbol_key);
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::postIndex */
    public function testOrganizationPostIndex()
    {
        $organization = BaseDataModel::ORGANIZATION;
        $paramams = [];

        $paramams["fields"][] = [
            "symbol_key" => "state",
            "name" => "State",
            "type" => FieldType::CHECKBOX,
            "default_value" => 'in_progress',
            "choices"=> 'in_progress,ready',
        ];

        $response =  $this->authorizedCall('POST', $organization.'/field', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("state", $responseData->data->list[0]->symbol_key);
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::postIndex */
    public function testJobPostIndex()
    {
        $job = BaseDataModel::JOB;
        $paramams = [];

        $paramams["fields"][] = [
            "symbol_key" => "state",
            "name" => "State",
            "type" => FieldType::CHECKBOX,
            "default_value" => 'in_progress',
            "choices"=> 'in_progress,ready',
        ];

        $response =  $this->authorizedCall('POST', $job.'/field', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("state", $responseData->data->list[0]->symbol_key);
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::postIndex */
    public function testCandidatePostIndex()
    {
        $candidate = BaseDataModel::CANDIDATE;
        $paramams = [];

        $paramams["fields"][] = [
            "symbol_key" => "is_married",
            "name" => "Is married",
            "type" => FieldType::BOOLEAN,
            "default_value" => 'yes',
            "choices"=> 'yes,no',
        ];

        $response =  $this->authorizedCall('POST', $candidate.'/field', $paramams);


        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("is_married", $responseData->data->list[0]->symbol_key);
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::putIndex */
    public function testCandidatePutIndex()
    {
        $candidate = BaseDataModel::CANDIDATE;
        $paramams = [];
        $paramams["fields"][] = [
            "id" => 2,
            "name" => "Select Country",
            "default_value" => "Greece",
            "choices"=> 'Greece,Spain',
        ];

        $response =  $this->authorizedCall('PUT', $candidate.'/field', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("Select Country", $responseData->data->list[0]->name);
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::putIndex */
    public function testOrganizationPutIndex()
    {
        $organization = BaseDataModel::ORGANIZATION;
        $paramams = [];
        $paramams["fields"][] = [
            "id" => 2,
            "name" => "My birth date",
            "default_value" => "",
            "choices"=> '',
        ];
        $paramams["fields"][] = [
            "id" => 3,
            "name" => "Zippy",
            "default_value" => "1111111",
            "choices"=> '',
        ];

        $response =  $this->authorizedCall('PUT', $organization.'/field', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("Zippy", $responseData->data->list[1]->name);
        $this->assertTrue((bool)$responseData->status);
    }

    /** @see Api\Field\Controller::putIndex */
    public function testAgencyPutIndex()
    {
       // App::register(\MissionNext\Provider\ErrorProvider::class);
        $agency = BaseDataModel::AGENCY;
        $paramams = [];
        $paramams["fields"][] = [
            "id" => 3,
            "name" => "FirstN",
            "default_value" => "Bob",
            "choices"=> '',
        ];
        $paramams["fields"][] = [
            "id" => 4,
            "name" => "LastN",
            "default_value" => "Dod",
            "choices"=> '',
        ];

        $response =  $this->authorizedCall('PUT', $agency.'/field', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("Dod", $responseData->data->list[1]->default_value[0]);
        $this->assertTrue((bool)$responseData->status);
    }


    /**
     * @see Api\Field\Controller::putIndex
     * @expectedException \MissionNext\Api\Exceptions\AuthenticationException
     */
    public function testFailurePutIndex()
    {
        $agency = BaseDataModel::AGENCY;
        $paramams = [];
        $paramams["fields"][] = [
            "id" => 3,
            "name" => "FirstN",
            "default_value" => "Bob",
            "choices"=> '',
        ];
        $this->applicationKey = 'failure';

        $this->authorizedCall('PUT', $agency.'/field', $paramams);
    }

    /**
     * @see Api\Field\Controller::deleteIndex
     *
     */
    public function testDeleteIndex()
    {
        $candidate = BaseDataModel::CANDIDATE;
        $ids = ['ids' => [ 1, 2]];
        $response =  $this->authorizedCall('DELETE', $candidate.'/field',[], $ids);

        $this->assertNotContains([1, 2], array_fetch($response->getData()->data->list, 'id'));
        $this->assertTrue((bool)$response->getData()->status);

    }

    /**
     * @see Api\Field\Controller::postModel
     *
     */
    public function testPostModelIndex()
    {
        $agency = BaseDataModel::AGENCY;
        $paramams = [];
        $paramams["fields"][] = [
            "id" => 1,
            "constraints" => "date",
        ];
        $paramams["fields"][] = [
            "id" => 2,
            "constraints" => "",
        ];
        $paramams["fields"][] = [
            "id" => 3,
            "constraints" => "",
        ];
        $paramams["fields"][] = [
            "id" => 4,
            "constraints" => "",
        ];

        $response =  $this->authorizedCall('POST', $agency.'/field/model', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("date", $responseData->data->list[0]->pivot->constraints);
        $this->assertTrue((bool)$responseData->status);
    }

    /**
     * @see Api\Field\Controller::postModel
     *
     */
    public function testPostJobModelIndex()
    {
        $job = BaseDataModel::JOB;
        $paramams = [];
        $paramams["fields"][] = [
            "id" => 1,
            "constraints" => "required",
        ];
        $paramams["fields"][] = [
            "id" => 2,
            "constraints" => "required|min:3",
        ];
        $paramams["fields"][] = [
            "id" => 3,
            "constraints" => "required|min:3",
        ];
        $paramams["fields"][] = [
            "id" => 4,
            "constraints" => "required",
        ];
        $paramams["fields"][] = [
            "id" => 5,
            "constraints" => "required",
        ];

        $response =  $this->authorizedCall('POST', $job.'/field/model', $paramams);

        $responseData = $response->getData();

        $this->assertEquals(count($paramams["fields"]), count($responseData->data->list));
        $this->assertEquals("required", $responseData->data->list[0]->pivot->constraints);
        $this->assertTrue((bool)$responseData->status);
    }

    /**
     * @see Api\Field\Controller::getModel
     *
     */
    public function testGetModelIndex()
    {
        $agency = BaseDataModel::AGENCY;

        $response =  $this->authorizedCall('GET', $agency.'/field/model');

        $responseData = $response->getData();

        $this->assertGreaterThan( 2,  count($responseData->data->list) );
        $this->assertTrue((bool)$responseData->status);
    }
} 