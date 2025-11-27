<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProbTableSecond extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prob_model', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->double('expected',9,6);
            $table->double('actual',9,6);
            $table->double('first',9,6);
            $table->double('second',9,6);
            $table->double('third',9,6);
            $table->double('fourth',9,6);
            $table->integer('steps');
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
        Schema::drop('prob_model');
    }
}
