<?php
use MissionNext\Models\Field\FieldType;
class FieldTypeSeeder extends BaseSeeder
{
    public function run()
    {
        DB::statement($this->getDbStatement()->truncateTable("field_types"));

        DB::table('field_types')->insert(array(
            array(
                "id" => FieldType::DATE,
                "name" => "date",
                "multiple" => false,

            ),
            array(
                "id" => FieldType::SELECT,
                "name" => "select",
                "multiple" => false,

            ),
            array(
                "id" => FieldType::INPUT,
                "name" => "input",
                "multiple" => false,

            ),
            array(
                "id" => FieldType::SELECT_MULTIPLE,
                "name" => "select_multiple",
                "multiple" => true,
            ),
            array(
                "id" => FieldType::TEXT,
                "name" => "text",
                "multiple" => false,
            ),
            array(
                "id" => FieldType::RADIO,
                "name" => "radio",
                "multiple" => false,
            ),
            array(
                "id" => FieldType::BOOLEAN,
                "name" => "boolean",
                "multiple" => false,
            ),
            array(
                "id" => FieldType::CHECKBOX,
                "name" => "checkbox",
                "multiple" => true,
            ),
            array(
                "id" => FieldType::FILE,
                "name" => "file",
                "multiple" => false,
            ),

        ));

    }

}