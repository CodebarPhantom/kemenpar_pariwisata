<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tourism_info_id');
            $table->string('name');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('disc_percentage');
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamps();

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
        Schema::dropIfExists('ticket_promotions');
    }
}
