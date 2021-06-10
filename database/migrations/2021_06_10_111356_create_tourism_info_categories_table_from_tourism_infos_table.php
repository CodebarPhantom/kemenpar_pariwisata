<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Tourism\TourismInfo;
use App\Models\Tourism\TourismInfoCategories;

class CreateTourismInfoCategoriesTableFromTourismInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tourismInfos = TourismInfo::where('price', '>', 0)->get();

        DB::beginTransaction();
        try {
            foreach ($tourismInfos as $tourismInfo) {
                $tourismInfoCategory = new TourismInfoCategories();
                $tourismInfoCategory->tourism_info_id = $tourismInfo->id;
                $tourismInfoCategory->name = 'Umum';
                $tourismInfoCategory->price = $tourismInfo->price;
                $tourismInfoCategory->created_at = $tourismInfo->created_at;
                $tourismInfoCategory->updated_at = $tourismInfo->updated_at;
                $tourismInfoCategory->save();
                $tourismInfo->price = 0;
                $tourismInfo->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return abort($e);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tourismInfoCategories = TourismInfoCategories::where('name', 'Umum')->get();

        DB::beginTransaction();
        try {
            foreach ($tourismInfoCategories as $tourismInfoCategory) {
                $tourismInfo = TourismInfo::where('id', $tourismInfoCategory->tourism_info_id)->first();
                $tourismInfo->price = $tourismInfoCategory->price;
                $tourismInfo->created_at = $tourismInfoCategory->created_at;
                $tourismInfo->updated_at = $tourismInfoCategory->updated_at;
                $tourismInfo->save();
            }

            Schema::disableForeignKeyConstraints();
            $tourismInfoCategory->delete();
            Schema::enableForeignKeyConstraints();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return abort($e);
        }
    }
}
