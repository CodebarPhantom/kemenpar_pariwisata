<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordinatePointTourismInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tourism_infos', function (Blueprint $table) {
            $table->string('latitude',50)->after('url_logo')->default(0);
            $table->string('longitude',50)->after('latitude')->default(0);

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tourism_infos', function (Blueprint $table) {
            //
        });
    }
}
