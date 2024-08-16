<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateDataModelFields extends Migration
{

    private function createModelToFields($role)
    {
        Schema::create('data_model_' . $role . '_fields', function (Blueprint $table) use ($role) {
            $table->unsignedInteger('data_model_id');
            $table->foreign("data_model_id")->references('id')->on('app_data_model')->onDelete('cascade');
            $table->unsignedInteger('field_id');
            $table->foreign("field_id")->references('id')->on($role . '_fields')->onDelete('cascade');

            $table->string('constraints')->nullable();
        });
    }

    private function dropModelToFields($role)
    {

        Schema::drop('data_model_' . $role . '_fields');

    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $this->createModelToFields(BaseDataModel::CANDIDATE);
      $this->createModelToFields(BaseDataModel::ORGANIZATION);
      $this->createModelToFields(BaseDataModel::AGENCY);
      $this->createModelToFields(BaseDataModel::JOB);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $this->dropModelToFields(BaseDataModel::CANDIDATE);
       $this->dropModelToFields(BaseDataModel::ORGANIZATION);
       $this->dropModelToFields(BaseDataModel::AGENCY);
       $this->dropModelToFields(BaseDataModel::JOB);
    }

}
