<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine', function($table)
        {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('medicine_name', 64)->unique();
            $table->string('medicine_scientific_name', 128);            
            $table->string('medicine_type', 32);
            $table->string('medicine_dosage', 16);
            $table->string('dar_license_number', 16);
            $table->string('mfg_license_number', 16);
            $table->enum('status', ['pending', 'active', 'deactive']);
            $table->string('created_by', 32)->nullable();
            $table->string('updated_by', 32)->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->index(['company_id', 'medicine_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medicine');
    }
}
