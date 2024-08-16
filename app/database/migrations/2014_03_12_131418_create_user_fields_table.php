<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateUserFieldsTable extends Migration
{

    private function createFieldsTable($role)
    {
        Schema::create($role . '_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol_key', 60)->unique();
            $table->string('name', 60);
            $table->unsignedInteger('type');
            $table->foreign("type")->references('id')->on('field_types');
            $table->string('default_value')->nullable();
        });
    }

    private function dropFieldsTable($role)
    {
        Schema::drop($role.'_fields');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createFieldsTable(BaseDataModel::CANDIDATE);
        $this->createFieldsTable(BaseDataModel::ORGANIZATION);
        $this->createFieldsTable(BaseDataModel::AGENCY);
        $this->createFieldsTable(BaseDataModel::JOB);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropFieldsTable(BaseDataModel::CANDIDATE);
        $this->dropFieldsTable(BaseDataModel::ORGANIZATION);
        $this->dropFieldsTable(BaseDataModel::AGENCY);
        $this->dropFieldsTable(BaseDataModel::JOB);
    }

}
