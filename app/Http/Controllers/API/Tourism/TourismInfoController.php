<?php

namespace App\Http\Controllers\API\Tourism;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use App\Models\Tourism\TourismInfoCategories;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables, Laratrust;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\Environment\Console;

class TourismInfoController extends Controller
{
    public function index()
    {
        $tourismInfos = TourismInfo::select('name','slug','url_cover_image')->where('is_active',1)->orderBy('name', 'ASC')->get();

        return response()->json($tourismInfos, 200);
    }

    public function show($slug)
    {
        $detailTourism = TourismInfo::with([
            'categories'=> function ($query) {
                $query->limit(2);
            }
        ])->where('slug',$slug)->first();

        return response()->json($detailTourism, 200);

    }

    public function categoryInfo(Request $request){
       
        $categoryIds = json_decode($request->tourism_info_category_id);

        Log::debug($categoryIds);

        foreach ($categoryIds as $categoryId) {
           $tourismInfoCategory = TourismInfoCategories::findOrFail($categoryId);
            $detailCategory[] =  [
                'id'=> $tourismInfoCategory->id,
                'tourism_info_id'=>$tourismInfoCategory->tourism_info_id,
                'price'=>$tourismInfoCategory->price,
                'name'=>$tourismInfoCategory->name
                ];
        }
           
       return response()->json(($detailCategory), 200);
    }
}
