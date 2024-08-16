<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class AddDictionaryIdToProfile extends Migration {

    private function updateProfile($role, $up = true)
    {
        if ($up) {
            Schema::table($role . '_profile', function (Blueprint $table) use ($role) {
                $table->unsignedInteger('dictionary_id')->nullable();
                $table->foreign('dictionary_id')->references('id')->on($role . '_dictionary')->onDelete('cascade');
            });
        } else {
            Schema::table($role . '_profile', function (Blueprint $table) use ($role) {
               $table->dropColumn('dictionary_id');
            });
        }
    }

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $this->updateProfile(BaseDataModel::CANDIDATE);
        $this->updateProfile(BaseDataModel::ORGANIZATION);
        $this->updateProfile(BaseDataModel::AGENCY);
        $this->updateProfile(BaseDataModel::JOB);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        $this->updateProfile(BaseDataModel::CANDIDATE, false);
        $this->updateProfile(BaseDataModel::ORGANIZATION, false);
        $this->updateProfile(BaseDataModel::AGENCY, false);
        $this->updateProfile(BaseDataModel::JOB, false);
	}

}
