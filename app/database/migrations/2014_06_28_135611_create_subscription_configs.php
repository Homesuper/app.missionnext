<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionConfigs extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_configs', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('app_id');
            $table->foreign('app_id')->references('id')->on('application')->onDelete('cascade');
            $table->string('role', 60);
            $table->string('partnership', 60);
            $table->float('price_month');
            $table->float('price_year');
            $table->unique(['app_id', 'role', 'partnership']);
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subscription_configs');
    }

}