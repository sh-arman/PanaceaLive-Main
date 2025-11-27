<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDummyFacebookUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dummy_facebook_user', function (Blueprint $table) {
            $table->increments('id');
            $table->index('id');
            $table->string('userId')->unique();
            $table->integer('sequence_num')->default(0);
            $table->integer('past_seq_no')->default(0);
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
        Schema::drop('dummy_facebook_user');
    }
}
