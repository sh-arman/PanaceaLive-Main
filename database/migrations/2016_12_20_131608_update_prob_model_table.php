<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProbModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prob_model', function (Blueprint $table) {
            $table->double('first');
            $table->double('second');
            $table->double('third');
            $table->double('fourth');
            $table->integer('steps');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prob_model', function (Blueprint $table) {
            $table->dropColumn('first');
            $table->dropColumn('second');
            $table->dropColumn('third');
            $table->dropColumn('fourth');
            $table->dropColumn('steps');
        });
    }
}
