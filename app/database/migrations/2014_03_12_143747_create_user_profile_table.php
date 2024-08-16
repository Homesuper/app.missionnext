<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $create =  function ($role, $isJob = false)
        {
            $name = $isJob ? "job" : "user";
            Schema::create($role . '_profile', function (Blueprint $table) use ($role, $name) {
                $table->unsignedInteger($name.'_id');
                $table->foreign($name."_id")->references('id')->on($name.'s')->onDelete('cascade');

                $table->unsignedInteger('field_id');
                $table->foreign("field_id")->references('id')->on($role . '_fields')->onDelete('cascade');

                $table->string('value')->nullable();
            });

        };

       $create(BaseDataModel::CANDIDATE);
       $create(BaseDataModel::ORGANIZATION);
       $create(BaseDataModel::AGENCY);
       $create(BaseDataModel::JOB, true);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $drop = function($role){
            Schema::drop($role . '_profile');
        };

       $drop(BaseDataModel::CANDIDATE);
       $drop(BaseDataModel::ORGANIZATION);
       $drop(BaseDataModel::AGENCY);
       $drop(BaseDataModel::JOB);
    }

}
