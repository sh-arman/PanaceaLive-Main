<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


class CreateReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('report', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name', 32);
            $table->string('email', 32);
            $table->string('phone_number', 32);
            $table->string('medicine_name', 32);
            $table->string('manufacturer', 32);
            $table->string('location', 255);
            $table->string('store_name', 32);
            $table->text('details');
            $table->string('image',255);
            $table->timestamp('created_time')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::drop('report');
    }
}
