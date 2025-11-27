<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProbModelActionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prob_model_action', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prob_model_id')->unsigned();
            $table->integer('progress_number')->unsigned();
            $table->string('update_details', 255);
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
        Schema::drop('prob_model_action');
    }
}
