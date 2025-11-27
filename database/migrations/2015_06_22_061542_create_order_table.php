<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_order', function($table)
        {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->integer('medicine_id')->unsigned();
            $table->date('mfg_date');
            $table->date('expiry_date');
            $table->string('batch_number', 16);
            $table->integer('quantity');
            $table->string('file', 64);
            $table->string('destination', 128)->nullable();
            $table->enum('status', ['pending', 'running', 'finished'])->default('running');
            $table->string('created_by', 32)->nullable();
            $table->string('updated_by', 32)->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('medicine_id')->references('id')->on('medicine')->onDelete('cascade');
            $table->index(['company_id', 'medicine_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('print_order');
    }
}
