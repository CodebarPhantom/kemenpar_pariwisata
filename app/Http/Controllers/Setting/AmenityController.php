<?php

namespace App\Http\Controllers\Setting;

use App\Models\Setting\Amenity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Lang, Auth, DB, Exception,Storage, Laratrust, DataTables, Alert;



class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Laratrust::isAbleTo('view-amenities')) return abort(404);
        return view('setting.amenity.index');

    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-amenities')) return abort(404);

        $amenities = Amenity::orderBy('name','ASC');

        return DataTables::of($amenities)
            ->editColumn('name',function($amenity){
                return '<i class="'.$amenity->icon.'"></i> '.$amenity->name;
            })
            ->editColumn('action',function($amenity){
                
                $edit =
                '<a href="' .
                route('setting.amenities.edit', $amenity->id) .
                '" class="btn btn-danger btn-flat btn-xs align-middle" title="' .
                Lang::get('Edit') .
                '"><i class="fa fa-pencil-alt fa-sm"></i></a>';

                return $edit;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw("name like ?", ["%$keyword%"]);
            })
            ->rawColumns(['name','action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Laratrust::isAbleTo('view-amenities')) return abort(404);

      
        return view('setting.amenity.create');
        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('view-amenities')) return abort(404);
        
        $this->validate($request, [
            'name' => 'required',
            'category' => 'required',
            'icon' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $amenity = new Amenity();
            $amenity->name = $request->name; 
            $amenity->category = $request->category;
            $amenity->icon = $request->icon;
            $amenity->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }

        $this->userLog('Membuat Fasilitas Baru ' . $amenity->name );
        Alert::alert('Success', 'Fasilitas Baru Berhasil Ditambahkan', 'success');
        return redirect()->route('setting.amenities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Amenity  $amenity
     * @return \Illuminate\Http\Response
     */
    public function show(Amenity $amenity)
    {
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Amenity  $amenity
     * @return \Illuminate\Http\Response
     */
    public function edit(Amenity $amenity)
    {
        if (!Laratrust::isAbleTo('view-amenities')) return abort(404);
        return view('setting.amenity.edit',compact('amenity'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Amenity  $amenity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Amenity $amenity)
    {
        DB::beginTransaction();
        try {

            $amenity->name = $request->name; 
            $amenity->category = $request->category;
            $amenity->icon = $request->icon;
            $amenity->save();
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }

        $this->userLog('Mengubah Fasilitas ' . $amenity->name );
        Alert::alert('Success', 'Fasilitas Berhasil Diperbarui', 'success');
        return redirect()->route('setting.amenities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Amenity  $amenity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amenity $amenity)
    {
        //
    }
}
