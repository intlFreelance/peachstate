<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results_log', function(Blueprint $table){
            $table->increments('id');
            $table->bigInteger('applicationId');
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('applicationStatus');
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
        Schema::dropIfExists('results_log');
    }
}
