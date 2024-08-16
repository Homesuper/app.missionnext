<?php
use MissionNext\Models\Application\Application;
use MissionNext\Models\Form\AppForm;
use MissionNext\Models\Form\FormGroup;
use MissionNext\Models\Field\FieldGroup;

class AppFormSeeder extends BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("app_forms"));
        DB::statement($this->getDbStatement()->truncateTable("form_groups"));
        DB::statement($this->getDbStatement()->truncateTable("group_fields"));
        /** @var  $application Application */
        if (!App::environment('testing')) {
          return;
        }
        $application = Application::find(1);
        $canDM = $application->DM(\MissionNext\Models\DataModel\BaseDataModel::CANDIDATE);

        $appForm1 = new AppForm();
        $appForm1->dataModel()->associate($canDM);
        $appForm1->symbol_key = "profile";
        $appForm1->name = "profile";
        $appForm1->save();

        $formGroup1 = new FormGroup();
        $formGroup1->symbol_key = "group_one";
        $formGroup1->name = "First Group";
        $formGroup1->order = 1;
        $formGroup1->depends_on = null;
        $formGroup1->is_outer_dependent = false;
        $formGroup1->form()->associate($appForm1);
        $formGroup1->save();

        $fieldsToIns = [

                array(
                    "group_id" => $formGroup1->id,
                    "symbol_key" => "birth_date",
                    "order" => 1,
                    "created_at" => (new DateTime())->format("Y-m-d"),
                    "updated_at" => (new DateTime())->format("Y-m-d")
                ),
            array(
                "group_id" => $formGroup1->id,
                "symbol_key" => "country",
                "order" => 1,
                "created_at" => (new DateTime())->format("Y-m-d"),
                "updated_at" => (new DateTime())->format("Y-m-d")
            )
            ];



        FieldGroup::insert($fieldsToIns);



        $formGroup2 = new FormGroup();
        $formGroup2->symbol_key = "group_two";
        $formGroup2->name = "Second Group";
        $formGroup2->order = 2;
        $formGroup1->depends_on = "birth_date";
        $formGroup1->is_outer_dependent = 1;
        $formGroup2->form()->associate($appForm1);
        $formGroup2->save();

        $fieldsToIns = [

            array(
                "group_id" => $formGroup1->id,
                "symbol_key" => "hobby",
                "order" => 1,
                "created_at" => (new DateTime())->format("Y-m-d"),
                "updated_at" => (new DateTime())->format("Y-m-d")
            ),

        ];
        FieldGroup::insert($fieldsToIns);

    }
}