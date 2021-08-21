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

class TourismInfoController extends Controller
{
    public function index()
    {
        $tourismInfos = TourismInfo::orderBy('name', 'ASC')->get();

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
}
