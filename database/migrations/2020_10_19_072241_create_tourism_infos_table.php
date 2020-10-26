<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourismInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tourism_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',100);
            $table->string('address',255);            
            $table->decimal('price', 18, 2)->default(0);
            $table->string('url_logo', 255)->nullable();
            $table->boolean('is_active')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tourism_infos');
    }
}
