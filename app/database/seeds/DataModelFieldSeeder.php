<?php

use MissionNext\Models\Application\Application;
use MissionNext\Models\DataModel\BaseDataModel;

class DataModelFieldSeeder extends BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("data_model_candidate_fields"));
        DB::statement($this->getDbStatement()->truncateTable("data_model_organization_fields"));
        DB::statement($this->getDbStatement()->truncateTable("data_model_job_fields"));

        /** @var  $application Application */
        $application = Application::find(1);

        /** @var  $app2 Application */
        $app2 = Application::find(2);

        $application->DM(BaseDataModel::CANDIDATE)
                    ->candidateFields()
                    ->sync([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14 ]);

        $app2->DM(BaseDataModel::CANDIDATE)
            ->candidateFields()
            ->sync([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14 ]);


        $application->DM(BaseDataModel::CANDIDATE)
            ->candidateFields()
            ->attach([  15 =>  ["constraints" => "mimes:pdf|max:20000" ] ]);
        $app2->DM(BaseDataModel::CANDIDATE)
            ->candidateFields()
            ->attach([  15 =>  ["constraints" => "mimes:pdf|max:20000" ] ]);


        $application->DM(BaseDataModel::ORGANIZATION)
            ->organizationFields()
            ->sync([ 1, 2, 3 ]);
        $app2->DM(BaseDataModel::ORGANIZATION)
            ->organizationFields()
            ->sync([ 1, 2, 3 ]);


        $application->DM(BaseDataModel::AGENCY)
        ->agencyFields()
        ->sync([ 1, 2, 3, 4 ]);
        $app2->DM(BaseDataModel::AGENCY)
            ->agencyFields()
            ->sync([ 1, 2, 3, 4 ]);


        $application->DM(BaseDataModel::JOB)
            ->jobFields()
            ->sync([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10  ]);
        $app2->DM(BaseDataModel::JOB)
        ->jobFields()
        ->sync([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10  ]);


    }
}