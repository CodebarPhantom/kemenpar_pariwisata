<?php

namespace App\Http\Controllers\Tourism;

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
        if (!Laratrust::isAbleTo('view-tourism-info')) {
            return abort(404);
        }
        return view('tourism.info.index');
    }

    public function tourismInfoData()
    {
        if (!Laratrust::isAbleTo('view-tourism-info')) {
            return abort(404);
        }

        $tourismInfos = TourismInfo::orderBy('name', 'ASC');

        if (!Laratrust::hasRole('superadmin')) {
            $tourismInfos = $tourismInfos->where('id', auth()->user()->tourism_info_id);
        }

        return DataTables::of($tourismInfos)
            ->editColumn('name', function ($tourismInfo) {
                return '<a href="' .
                    $tourismInfo->url_logo .
                    '" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="30px" height="30px" src="' .
                    $tourismInfo->url_logo .
                    '"></a>' .
                    ' ' .
                    $tourismInfo->name;
            })
            ->editColumn('status', function ($tourismInfo) {
                if ($tourismInfo->is_active == 1) {
                    $color = 'success';
                    $status = 'Active';
                } elseif ($tourismInfo->is_active == 0) {
                    $color = 'danger';
                    $status = 'Inactive';
                }
                return '<span class="badge bg-' . $color . ' align-middle">' . Lang::get($status) . '</span>';
            })
            ->editColumn('price', function ($tourismInfo) {
                $tourismInfoCategories = TourismInfoCategories::where('tourism_info_id', $tourismInfo->id)->get();

                $priceField = '';

                if (count($tourismInfoCategories) == 0) {
                    $priceField =
                        '<div class="align-middle">Rp. ' . number_format($tourismInfo->price, 2, ',', '.') . '</div>';
                } else {
                    foreach ($tourismInfoCategories as $tourismInfoCategory) {
                        $priceField .=
                            '<div class="align-middle">' .
                            $tourismInfoCategory->name .
                            ': Rp. ' .
                            number_format($tourismInfoCategory->price, 2, ',', '.') .
                            '</div>';
                    }
                }

                return $priceField;
            })
            ->editColumn('action', function ($tourismInfo) {
                $show =
                    '<a href="' .
                    route('tourism-info.show', $tourismInfo->id) .
                    '" class="btn btn-info btn-flat btn-xs align-middle" title="' .
                    Lang::get('Show') .
                    '"><i class="fa fa-eye fa-sm"></i></a>';
                $edit =
                    '<a href="' .
                    route('tourism-info.edit', $tourismInfo->id) .
                    '" class="btn btn-danger btn-flat btn-xs align-middle" title="' .
                    Lang::get('Edit') .
                    '"><i class="fa fa-pencil-alt fa-sm"></i></a>';
                return $show . $edit;
            })
            ->rawColumns(['name', 'action', 'status', 'price'])
            ->make(true);
    }

    public function show($id)
    {
        $this->checkPermission($id);

        $tourismInfo = TourismInfo::findOrFail($id);
        $tourismInfoCategories = TourismInfoCategories::where('tourism_info_id', $tourismInfo->id)->get();

        return view('tourism.info.show', compact('tourismInfo', 'tourismInfoCategories'));
    }
    public function edit($id)
    {
        $this->checkPermission($id);

        $tourismInfo = TourismInfo::findOrFail($id);
        $tourismInfoCategories = TourismInfoCategories::where('tourism_info_id', $tourismInfo->id)->get();

        return view('tourism.info.edit', compact('tourismInfo', 'tourismInfoCategories'));
    }

    public function create()
    {
        if (!Laratrust::hasRole('superadmin')) {
            // if (!Laratrust::isAbleTo('view-tourism-info')) {
            return abort(404);
        }
        return view('tourism.info.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::hasRole('superadmin')) {
            // if (!Laratrust::isAbleTo('view-tourism-info')) {
            return abort(404);
        }
        $this->validate($request, [
            'tourismName' => 'required',
            'tourismCategories' => 'required',
            'tourismPrice' => 'required',
            'tourismLogo' => 'required',
            'tourismAddress' => 'required',
            'tourismCode' => 'required',
            'tourismPosition' => 'required',
            'tourismManageBy' => 'required',
        ]);

        $tourismPosition = explode(',', $request->tourismPosition);
        DB::beginTransaction();
        try {
            $filePathLogo = $request->tourismLogo->store('public/logos');
            $fileUrlLogo = url('/storage') . str_replace('public', '', $filePathLogo);

            $tourismInfo = new TourismInfo();
            $tourismInfo->name = $request->tourismName;
            $tourismInfo->code = str::upper($request->tourismCode);
            $tourismInfo->address = $request->tourismAddress;
            $tourismInfo->url_logo = $fileUrlLogo;
            $tourismInfo->price = 0;
            $tourismInfo->is_active = 1;
            $tourismInfo->latitude = $tourismPosition[0];
            $tourismInfo->longitude = $tourismPosition[1];
            $tourismInfo->manage_by = $request->tourismManageBy;
            $tourismInfo->insurance = $request->tourismInsurance ? $request->tourismInsurance : null;
            $tourismInfo->note1 = $request->tourismNote1 ? $request->tourismNote1 : null;
            if ($request->tourismLogoBumdes) {
                $filePathPhotoBumdes = $request->tourismLogoBumdes->store('public/logos/bumdes');
                $fileUrlPhotoBumdes = url('/storage') . str_replace('public', '', $filePathPhotoBumdes);
                $tourismInfo->logo_bumdes = $fileUrlPhotoBumdes;
            }
            $tourismInfo->save();

            foreach ($request->tourismCategories as $i => $tourismName) {
                if (!!$tourismName && $request->tourismPrice[$i] > 0) {
                    $tourismInfoCategories = new TourismInfoCategories();
                    $tourismInfoCategories->tourism_info_id = $tourismInfo->id;
                    $tourismInfoCategories->name = $tourismName;
                    $tourismInfoCategories->price = $request->tourismPrice[$i];
                    $tourismInfoCategories->save();
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Membuat Tempat Pariwisata Baru ' . $request->tourismName);
        Alert::alert('Success', 'Tempat Pariwisata Telah di Daftarkan', 'success');
        return redirect()->route('tourism-info.index');
    }

    public function update($id, Request $request)
    {
        $this->checkPermission($id);

        if (!Laratrust::isAbleTo('view-tourism-info')) {
            return abort(404);
        }

        $this->validate($request, [
            'tourismName' => 'required',
            'tourismCategories' => 'required',
            'tourismPrice' => 'required',
            'tourismAddress' => 'required',
            'tourismPosition' => 'required',
            'tourismManageBy' => 'required',
        ]);
        $tourismInfo = TourismInfo::findOrFail($id);

        $tourismPosition = explode(',', $request->tourismPosition);
        DB::beginTransaction();
        try {
            if ($request->tourismLogo) {
                Storage::delete(str_replace(url('storage'), 'public', $tourismInfo->url_logo));
                $filePathLogo = $request->tourismLogo->store('public/logos');
                $fileUrlLogo = url('/storage') . str_replace('public', '', $filePathLogo);
                $tourismInfo->url_logo = $fileUrlLogo;
            }
            if ($request->tourismLogoBumdes) {
                Storage::delete(str_replace(url('storage'), 'public', $tourismInfo->logo_bumdes));
                $filePathPhotoBumdes = $request->tourismLogoBumdes->store('public/logos/bumdes');
                $fileUrlPhotoBumdes = url('/storage') . str_replace('public', '', $filePathPhotoBumdes);
                $tourismInfo->logo_bumdes = $fileUrlPhotoBumdes;
            }
            $tourismInfo->name = $request->tourismName;
            $tourismInfo->address = $request->tourismAddress;
            $tourismInfo->price = 0;
            $tourismInfo->is_active = $request->is_active;
            $tourismInfo->latitude = $tourismPosition[0];
            $tourismInfo->longitude = $tourismPosition[1];
            $tourismInfo->manage_by = $request->tourismManageBy;
            $tourismInfo->insurance = $request->tourismInsurance ? $request->tourismInsurance : null;
            $tourismInfo->note1 = $request->tourismNote1 ? $request->tourismNote1 : null;
            $tourismInfo->save();

            $categories = [];

            foreach ($request->tourismCategories as $i => $tourismCategory) {
                if (!!$tourismCategory && $request->tourismPrice[$i] > 0) {
                    $tourismInfoCategories = TourismInfoCategories::find($request->tourismCategoriesId[$i]);
                    if (!$tourismInfoCategories) {
                        $tourismInfoCategories = new TourismInfoCategories();
                    }

                    $tourismInfoCategories->tourism_info_id = $tourismInfo->id;
                    $tourismInfoCategories->name = $request->tourismCategories[$i];
                    $tourismInfoCategories->price = $request->tourismPrice[$i];
                    $tourismInfoCategories->save();

                    array_push($categories, $tourismInfoCategories->id);
                }
            }

            Schema::disableForeignKeyConstraints();
            TourismInfoCategories::where('tourism_info_id', $tourismInfo->id)
                ->whereNotIn('id', $categories)
                ->delete();
            Schema::enableForeignKeyConstraints();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Mengubah Tempat Pariwisata ' . $request->tourismName);

        Alert::alert('Success', 'Pariwisata ' . $tourismInfo->name . ' Telah di Ubah', 'info');
        return redirect()->route('tourism-info.index');
    }

    private function checkPermission($id)
    {
        if (!Laratrust::hasRole('superadmin') && $id != auth()->user()->tourism_info_id) {
            return abort(
                config('laratrust.middleware.handlers.abort.code'),
                config('laratrust.middleware.handlers.abort.message')
            );
        }
    }
}
