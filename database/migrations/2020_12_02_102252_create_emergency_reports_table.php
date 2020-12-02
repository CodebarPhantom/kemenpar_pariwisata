<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmergencyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tourism_info_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title',100);
            $table->text('description');
            $table->unsignedTinyInteger('status')->default(1)->comment('1= baru. 2=dalam penanganan, 3=selesai');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tourism_info_id')->references('id')->on('tourism_infos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emergency_reports');
    }
}
