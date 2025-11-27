<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function($table)
        {
            $table->increments('id');
            $table->string('company_name', 32)->unique();
            $table->string('display_name', 32);
            $table->text('company_address');
            $table->string('contact_name', 32)->nullable();
            $table->string('contact_designation', 32)->nullable();
            $table->string('contact_number', 16)->nullable();
            $table->string('contact_email', 32)->nullable();
            $table->enum('status', ['pending', 'active', 'deactive']);
            $table->string('created_by', 32)->nullable();
            $table->string('updated_by', 32)->nullable();
            $table->timestamps();
            $table->index(['company_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company');
    }
}
