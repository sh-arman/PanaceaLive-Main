<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCode2019Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('code2019', function($table)
        {
            $table->increments('id');
            $table->string('code', 16)->unique();
            $table->integer('status')->unsigned()->default(0);
            $table->index(['code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('code2019');
    }
}
