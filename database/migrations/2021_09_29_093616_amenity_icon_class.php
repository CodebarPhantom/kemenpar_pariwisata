<?php

use App\Models\Setting\Amenity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AmenityIconClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->string('icon_class')->after('icon');
            
        });

        $amenities = Amenity::select('id','icon')->get();

        foreach ($amenities as $amenity) {
            $amenity->icon_class = Str::camel(str_replace('fas fa','',$amenity->icon));
            $amenity->save();
        }
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amenities', function (Blueprint $table) {
            //
        });
    }
}
