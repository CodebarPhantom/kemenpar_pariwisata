<?php

use App\Models\Tourism\TourismInfo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddOverviewContactTourismInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tourism_infos', function (Blueprint $table) {
            $table->string('note1')->nullable()->change();
            $table->text('overview')->after('insurance');
            $table->string('phone',25)->after('address');
            $table->string('facebook',100)->nullable()->after('phone');
            $table->string('instagram',100)->nullable()->after('facebook');
            $table->string('url_cover_image')->after('url_logo');
            $table->string('opening_hour',750)->after('code');
            $table->string('slug',50)->after('name');
        });

        $openingHour = [
                ['day'=>'Senin', 'opening_hour'=>'10.00 - 23.50'],
                ['day'=>'Selasa', 'opening_hour'=>'10.00 - 23.50'],
                ['day'=>'Rabu', 'opening_hour'=>'10.00 - 23.50'],
                ['day'=>'Kamis', 'opening_hour'=>'10.00 - 23.50'],
                ['day'=>'Jumat', 'opening_hour'=>'10.00 - 23.50'],
                ['day'=>'Sabtu', 'opening_hour'=>'10.00 - 23.50'],
                ['day'=>'Minggu', 'opening_hour'=>'10.00 - 23.50']
        ];

        $tourismInfos = TourismInfo::get();
        foreach ($tourismInfos as $tourismInfo) {
            $tourismInfo->slug = Str::slug($tourismInfo->name,'-');
            $tourismInfo->overview = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed egestas elit id purus ultricies, nec iaculis diam convallis. Vestibulum gravida, ipsum sed malesuada dictum, erat odio luctus libero, ac imperdiet mauris lorem eu tortor. Aenean aliquet, ipsum id tempus euismod, nulla enim fermentum tortor, eget malesuada urna nisi a mauris. Donec sit amet porttitor velit, eget sollicitudin ipsum. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Ut accumsan, lacus nec elementum euismod, erat tortor dapibus neque, a laoreet dui ligula ac risus. Donec sagittis tempus dolor, eu posuere diam dapibus vel. Praesent efficitur mattis odio sit amet convallis. Morbi mollis arcu sit amet lectus dapibus, in ultrices ligula blandit.";
            $tourismInfo->phone = '-';
            $tourismInfo->opening_hour = json_encode($openingHour);
            $tourismInfo->save();
        }
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
