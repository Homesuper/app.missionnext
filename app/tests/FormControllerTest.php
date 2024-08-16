<?php
use MissionNext\Models\DataModel\BaseDataModel;

/**
 * Class FormControllerTest
 * @see Controller
 */
class FormControllerTest extends TestCase
{
    /**
     * @see Controller::putIndex()
     */
    public function testPutCandidateIndex()
    {
        $type = BaseDataModel::CANDIDATE;
        $formName = 'profile';

        $groups = [];
        $groups['groups'][] = ['name' => 'Group One', 'symbol_key' => 'group_one',
            'order' => 1, 'depends_on' => null,
            'is_outer_dependent' => null,
            'fields' => [
                0 => ['symbol_key' => 'birth_date', 'order' => 2,],
                1 => ['symbol_key' => 'zip_code', 'order' => 1,],

            ]
        ];
        $groups['groups'][] = ['name' => 'Group Two', 'symbol_key' => 'group_two',
            'order' => 1, 'depends_on' => 'birth_date',
            'is_outer_dependent' => null,
            'fields' => [
                0 => ['symbol_key' => 'occupation', 'order' => 2,],
                1 => ['symbol_key' => 'hobby', 'order' => 1,],

            ]
        ];

        $response = $this->authorizedCall('PUT', $type . '/' . $formName . '/form', $groups);
        $responseData = $response->getData();

        $this->assertEquals(count($groups["groups"]), count($responseData->data->list));
        $this->assertEquals("birth_date", $responseData->data->list[0]->fields[0]->symbol_key);
        $this->assertTrue((bool)$responseData->status);
    }

    /**
     * @see Controller::getIndex()
     */
    public function testGetCandidateIndex()
    {
        $type = BaseDataModel::CANDIDATE;
        $formName = 'profile';

        $response = $this->authorizedCall('GET', $type . '/' . $formName . '/form');
        $responseData = $response->getData();

        $this->assertGreaterThan(0, count($responseData->data->list));
        $this->assertTrue((bool)$responseData->status);
    }
} 