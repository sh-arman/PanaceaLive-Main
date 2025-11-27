<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookUserMaxproTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_user_maxpro', function (Blueprint $table) {
            $table->increments('id');
            $table->index('id');
            $table->string('userId')->unique();
            $table->string('name');
            $table->string('phone_number');
            $table->string('location');
            $table->boolean('terms_conditions')->default(0);
            $table->integer('past_seq_no')->default(0);
            $table->integer('sequence_num')->default(0);
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
        Schema::drop('facebook_user_maxpro');
    }
}
