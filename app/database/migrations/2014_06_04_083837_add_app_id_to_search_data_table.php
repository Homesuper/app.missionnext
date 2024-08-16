<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppIdToSearchDataTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('search_data', function(Blueprint $table)
        {
            $table->unsignedInteger('app_id')->default(1);
            $table->foreign("app_id")->references('id')->on('application')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('search_data', function(Blueprint $table)
        {
            $table->dropForeign("jobs_app_id_foreign");
            $table->dropColumn("app_id");
        });
    }

}
