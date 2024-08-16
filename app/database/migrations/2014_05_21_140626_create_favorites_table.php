<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoritesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('target_type', 30)->index();
            $table->integer('target_id')->index();
            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
            $table->timestamps();
            $table->unique(array('app_id', 'user_id', 'target_id', 'target_type'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('favorite');
    }

}
