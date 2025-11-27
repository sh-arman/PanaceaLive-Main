<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SMS_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id');
            $table->string('service_id');
            $table->string('message');
            $table->string('mobile_no');
            $table->boolean('completed')->default(0);
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
        Schema::drop('SMS_records');
    }
}
