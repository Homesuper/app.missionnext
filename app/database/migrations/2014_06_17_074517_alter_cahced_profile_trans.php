<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class AlterCahcedProfileTrans extends Migration {

    private function updateProfile($role, $up = true)
    {
        $tableName = $role . '_cached_profile_trans';

        if ($up) {
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN lang_id DROP not null");

        } else {
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN lang_id SET not null");
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
