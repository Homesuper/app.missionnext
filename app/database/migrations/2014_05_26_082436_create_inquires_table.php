<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiresTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquires', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('candidate_id');
            $table->foreign("candidate_id")->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger("job_id");
            $table->foreign("job_id")->references('id')->on('jobs')->onDelete('cascade');
            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
            $table->string("status", 40)->default(\MissionNext\Models\Inquire\Inquire::STATUS_INQUIRED);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inquires');
    }

}
