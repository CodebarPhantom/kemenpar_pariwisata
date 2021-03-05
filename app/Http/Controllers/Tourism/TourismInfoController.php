<?php

namespace App\Http\Controllers\Tourism;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use Auth, DB, View, DataTables, Alert, Lang, Laratrust, Storage,Str;


class TourismInfoController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-tourism-info')) return abort(404);
        return view('tourism.info.index');
    }    

    public function tourismInfoData()
    {
        if (!Laratrust::isAbleTo('view-tourism-info')) return abort(404);

        $tourismInfos = TourismInfo::orderBy('name','ASC');        
        return DataTables::of($tourismInfos) 
            ->editColumn('name',function($tourismInfo){
                
                return '<a href="'.$tourismInfo->url_logo.'" target="_blank"><img alt="Avatar" class="table-avatar align-middle rounded" width="30px" height="30px" src="'.$tourismInfo->url_logo.'"></a>'.' '.$tourismInfo->name;
            })
            ->editColumn('status',function($tourismInfo){
                if($tourismInfo->is_active == 1){
                    $color = 'success'; $status = 'Active';
                }elseif($tourismInfo->is_active == 0){
                    $color = 'danger'; $status = 'Inactive';
                }
                return '<span class="badge bg-'.$color.' align-middle">'.Lang::get($status).'</span>';
            })  
            ->editColumn('price',function($tourismInfo){                
                return '<div class="align-middle">'.number_format($tourismInfo->price).'</div>';
            })      
            ->editColumn('action',function($tourismInfo){
                $show =  '<a href="'.route('tourism-info.show',$tourismInfo->id).'" class="btn btn-info btn-flat btn-xs align-middle" title="'.Lang::get('Show').'"><i class="fa fa-eye fa-sm"></i></a>';
                $edit =  '<a href="'.route('tourism-info.edit',$tourismInfo->id).'" class="btn btn-danger btn-flat btn-xs align-middle" title="'.Lang::get('Edit').'"><i class="fa fa-pencil-alt fa-sm"></i></a>';
                 return $show.$edit;
            })
            ->rawColumns(['name','action','status','price'])
            ->make(true);

            
    }
    
    public function show($id)
    {
        $tourismInfo = TourismInfo::findOrFail($id);
        return view('tourism.info.show', compact('tourismInfo'));
    }
    public function edit($id)
    {
        $tourismInfo = TourismInfo::findOrFail($id);
        return view('tourism.info.edit', compact('tourismInfo'));
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('view-tourism-info')) return abort(404);
        return view('tourism.info.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('view-tourism-info')) return abort(404);
        $this->validate($request, [            
            'tourismName'=> 'required',
            'tourismPrice'=>'required',
            'tourismLogo'=>'required',
            'tourismAddress'=>'required',
            'tourismCode'=>'required',
            'tourismPosition'=>'required',
            'tourismManageBy'=>'required',

        ]);  
        $tourismPosition = explode(",", $request->tourismPosition);
        DB::beginTransaction();
        try {
            $filePathLogo = $request->tourismLogo->store('public/logos');
            $fileUrlLogo= url('/storage') . str_replace('public','', $filePathLogo);

            $tourismInfo = new TourismInfo;
            $tourismInfo->name = $request->tourismName;
            $tourismInfo->code = str::upper($request->tourismCode);
            $tourismInfo->address = $request->tourismAddress;
            $tourismInfo->url_logo = $fileUrlLogo;
            $tourismInfo->price = $request->tourismPrice;
            $tourismInfo->is_active = 1;
            $tourismInfo->latitude = $tourismPosition[0];
            $tourismInfo->longitude = $tourismPosition[1];
            $tourismInfo->manage_by = $request->tourismManageBy;
            $tourismInfo->insurance = $request->tourismInsurance ? $request->tourismInsurance : NULL;
            $tourismInfo->note1 = $request->tourismNote1 ?  $request->tourismNote1 : NULL;
            $tourismInfo->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Membuat Tempat Pariwisata Baru '.$request->tourismName);
        Alert::alert('Success', 'Tempat Pariwisata Telah di Daftarkan', 'success');
        return redirect()->route('tourism-info.index');

    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('view-tourism-info')) return abort(404);
        $this->validate($request, [            
            'tourismName'=> 'required',
            'tourismPrice'=>'required',
            'tourismAddress'=>'required',
            'tourismPosition'=>'required',
            'tourismManageBy'=>'required',
        ]);  
        $tourismInfo = TourismInfo::findOrFail($id);
        
        $tourismPosition = explode(",", $request->tourismPosition);
        DB::beginTransaction();
        try {
            if ($request->tourismLogo){
                Storage::delete(str_replace(url('storage'), 'public', $tourismInfo->url_logo));
                $filePathLogo = $request->tourismLogo->store('public/logos');
                $fileUrlLogo= url('/storage') . str_replace('public','', $filePathLogo);
                $tourismInfo->url_logo = $fileUrlLogo;
            }
            $tourismInfo->name = $request->tourismName;
            $tourismInfo->address = $request->tourismAddress;
            $tourismInfo->price = $request->tourismPrice;
            $tourismInfo->is_active = $request->is_active;            
            $tourismInfo->latitude = $tourismPosition[0];
            $tourismInfo->longitude = $tourismPosition[1];
            $tourismInfo->manage_by = $request->tourismManageBy;
            $tourismInfo->insurance = $request->tourismInsurance ? $request->tourismInsurance : NULL;
            $tourismInfo->note1 = $request->tourismNote1 ?  $request->tourismNote1 : NULL;
            $tourismInfo->save();
            
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Mengubah Tempat Pariwisata '.$request->tourismName);

        Alert::alert('Success', 'Pariwisata '.$tourismInfo->name.' Telah di Ubah', 'info');
        return redirect()->route('tourism-info.index');
    }

    
}
