<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use MissionNext\Models\DataModel\BaseDataModel;

class AddOnDeleteUser extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            foreach(BaseDataModel::userRoles() as $role) {
                DB::statement("
                ALTER TABLE {$role}_cached_profile
                DROP CONSTRAINT {$role}_user_id_unique,
                ADD CONSTRAINT  {$role}_user_id_foreign
                    FOREIGN KEY (id)
                    REFERENCES users(id)
                    ON DELETE CASCADE
                ");
            }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        foreach(BaseDataModel::userRoles() as $role) {
            DB::statement("
            ALTER TABLE {$role}_cached_profile
            DROP CONSTRAINT {$role}_user_id_foreign,
            ADD CONSTRAINT  {$role}_user_id_unique
                UNIQUE (id)
            ");
        }
    }

}
