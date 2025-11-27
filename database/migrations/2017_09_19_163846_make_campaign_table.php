<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('company_admin_id');
            $table->integer('amount');
            $table->string('language');
            $table->string('campaign_name')->nullable();
            $table->string('filename')->nullable();
            $table->string('product')->nullable();
            $table->string('message');
            $table->string('operator')->nullable();
            $table->dateTime('execution_time')->nullable();
            $table->integer('interval')->nullable();
            $table->enum('status', ['completed', 'cancelled', 'ongoing'])->default('ongoing');
            $table->integer('case');
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
        Schema::drop('campaign');
    }
}
