<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_history', function($table)
        {
            $table->increments('id');
            $table->string('phone_number', 16);
            $table->string('code', 16);
            $table->string('remarks', 32)->nullable();
            $table->string('location', 64)->nullable();
            $table->enum('source', ['web', 'app', 'sms'])->default('web');
            $table->timestamps();
            $table->index(['code', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('check_history');
    }
}
