<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecondaryTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('primary_tests_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('primary_tests_id')->references('id')->on('primary_tests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('secondary_tests');
    }
}
