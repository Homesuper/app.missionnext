<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateChoiceValuesTable extends Migration
{

    private function createDictionary($role)
    {
        Schema::create($role . '_dictionary', function (Blueprint $table) use ($role) {
            $table->increments('id');
            $table->unsignedInteger('field_id');
            $table->foreign("field_id")->references('id')->on($role . '_fields')->onDelete('cascade');
            $table->string("value")->nullable();
        });

    }

    private function dropDictionary($role)
    {
        Schema::drop($role . '_dictionary');

    }


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $this->createDictionary(BaseDataModel::CANDIDATE);
       $this->createDictionary(BaseDataModel::ORGANIZATION);
       $this->createDictionary(BaseDataModel::AGENCY);
       $this->createDictionary(BaseDataModel::JOB);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       $this->dropDictionary(BaseDataModel::CANDIDATE);
       $this->dropDictionary(BaseDataModel::ORGANIZATION);
       $this->dropDictionary(BaseDataModel::AGENCY);
       $this->dropDictionary(BaseDataModel::JOB);
    }

}
