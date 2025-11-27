<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_verification', function (Blueprint $table) {
            $table->increments('id');
            $table->index('id');
            $table->string('userId')->unique();
            $table->string('code',4);
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at');
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
        Schema::drop('facebook_verification');
    }
}
