<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_user', function (Blueprint $table) {
            $table->increments('id');
            $table->index('id');
            $table->string('userId')->unique();
            $table->string('name');
            $table->string('phone_number');
            $table->string('location');
            $table->boolean('terms_conditions')->default(0);
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
        Schema::drop('facebook_user');
    }
}
