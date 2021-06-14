<?php

namespace App\Http\Controllers\API\Emergency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Emergency\EmergencyReport;

class EmergencyController extends Controller
{
    public function index()
    {
        $result = ['emergency' => EmergencyReport::where('tourism_info_id', auth()->user()->tourism_info_id)->get()];

        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'title' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $emergencyReport = new EmergencyReport();
            $emergencyReport->title = $request->title;
            $emergencyReport->description = $request->description;
            $emergencyReport->user_id = auth()->user()->id;
            $emergencyReport->tourism_info_id = auth()->user()->tourism_info_id;
            $emergencyReport->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['errors' => $e], 500);
        }
        $this->userLog('Membuat Laporan Keadaan Darurat ' . $request->promotion_name);

        $result = [
            'emergency' => EmergencyReport::where('id', $emergencyReport->id)->get(),
        ];

        return response()->json($result, 200);
    }

    public function show($id)
    {
        $result = [
            'emergency' => EmergencyReport::where('id', $id)
                ->where('tourism_info_id', auth()->user()->tourism_info_id)
                ->get(),
        ];

        return response()->json($result, 200);
    }
}
