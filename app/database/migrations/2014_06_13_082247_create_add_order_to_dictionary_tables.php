<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class CreateAddOrderToDictionaryTables extends Migration {


    private function updateDictionary($role)
    {
        Schema::table($role . '_dictionary', function (Blueprint $table) use ($role) {
            $table->integer("order")->unsigned()->default(0);
        });

    }

    private function revertDictionary($role)
    {
        Schema::table($role . '_dictionary', function (Blueprint $table) use ($role) {
            $table->dropColumn('order');
        });
    }

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->updateDictionary(BaseDataModel::CANDIDATE);
		$this->updateDictionary(BaseDataModel::ORGANIZATION);
		$this->updateDictionary(BaseDataModel::AGENCY);
		$this->updateDictionary(BaseDataModel::JOB);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        $this->revertDictionary(BaseDataModel::CANDIDATE);
        $this->revertDictionary(BaseDataModel::ORGANIZATION);
        $this->revertDictionary(BaseDataModel::AGENCY);
        $this->revertDictionary(BaseDataModel::JOB);
	}

}
