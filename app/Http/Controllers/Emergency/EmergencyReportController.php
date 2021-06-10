<?php

namespace App\Http\Controllers\Emergency;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Emergency\EmergencyReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use RealRashid\SweetAlert\Facades\Alert;
use Exception, Laratrust, DataTables;

class EmergencyReportController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-emergency-report')) {
            return abort(404);
        }
        return view('emergency.index');
    }

    public function data()
    {
        if (!Laratrust::isAbleTo('view-emergency-report')) {
            return abort(404);
        }

        $emergencyReports = EmergencyReport::select(
            'emergency_reports.id',
            'us.name as user_name',
            'ti.name as tourism_name',
            'emergency_reports.title',
            'emergency_reports.status',
            'emergency_reports.created_at'
        )
            ->leftJoin('users as us', 'us.id', '=', 'emergency_reports.user_id')
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'emergency_reports.tourism_info_id');

        if (!Laratrust::hasRole('superadmin')) {
            $emergencyReports = $emergencyReports->where('ti.id', auth()->user()->tourism_info_id);
        }

        $emergencyReports = $emergencyReports->orderBy('emergency_reports.created_at', 'DESC');

        return DataTables::of($emergencyReports)
            ->editColumn('user_name', function ($emergencyReport) {
                return $emergencyReport->user_name;
            })
            ->editColumn('status', function ($emergencyReport) {
                if ($emergencyReport->status == 1) {
                    $color = 'danger';
                    $status = 'New';
                } elseif ($emergencyReport->status == 2) {
                    $color = 'warning';
                    $status = 'In Handling';
                } elseif ($emergencyReport->status == 3) {
                    $color = 'success';
                    $status = 'Done';
                }
                return '<span class="badge bg-' . $color . ' align-middle">' . Lang::get($status) . '</span>';
            })
            ->editColumn('date_report', function ($emergencyReport) {
                return $emergencyReport->created_at
                    ? with(new Carbon($emergencyReport->created_at))->translatedFormat('D, d-m-Y H:i')
                    : '-';
            })
            ->editColumn('summary', function ($emergencyReport) {
                return $emergencyReport->title;
            })

            ->editColumn('action', function ($emergencyReport) {
                $show =
                    '<a href="' .
                    route('report-emergency.show', $emergencyReport->id) .
                    '" class="btn btn-info btn-flat btn-xs align-middle" title="' .
                    Lang::get('Show') .
                    '"><i class="fa fa-eye fa-sm"></i></a>';
                $edit =
                    '<a href="' .
                    route('report-emergency.edit', $emergencyReport->id) .
                    '" class="btn btn-danger btn-flat btn-xs align-middle" title="' .
                    Lang::get('Respond') .
                    '"><i class="fa fa-exclamation-triangle fa-sm"></i></a>';

                return $show .
                    ($emergencyReport->status == 1 ||
                    ($emergencyReport->status == 2 && Laratrust::isAbleTo('view-emergency-report'))
                        ? $edit
                        : '');
            })

            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw('us.name like ?', ["%$keyword%"]);
            })

            ->filterColumn('date_report', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(emergency_reports.created_at,'%d-%m-%Y %H:%i') like ?", ["%$keyword%"]);
            })
            ->filterColumn('tourism_name', function ($query, $keyword) {
                $query->whereRaw('ti.name like ?', ["%$keyword%"]);
            })
            ->rawColumns(['name', 'action', 'status'])
            ->make(true);
    }

    public function create()
    {
        if (!Laratrust::isAbleTo('create-emergency-report')) {
            return abort(404);
        }

        return view('emergency.create');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('create-emergency-report')) {
            return abort(404);
        }

        $this->validate($request, [
            'description' => 'required',
            'title' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $emergencyReport = new EmergencyReport();
            $emergencyReport->title = $request->title;
            $emergencyReport->description = $request->description;
            $emergencyReport->user_id = Auth::user()->id;
            $emergencyReport->tourism_info_id = Auth::user()->tourism_info_id;
            $emergencyReport->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Membuat Laporan Keadaan Darurat ' . $request->promotion_name);
        Alert::alert('Success', 'Keadaan Darurat Berhasil Dilaporkan', 'success');
        return redirect()->route('ticket.index');
    }

    public function show($id)
    {
        $emergencyReport = EmergencyReport::select(
            'emergency_reports.id',
            'us.name as user_name',
            'ti.name as tourism_name',
            'emergency_reports.title',
            'emergency_reports.status',
            'emergency_reports.created_at',
            'emergency_reports.description'
        )
            ->leftJoin('users as us', 'us.id', '=', 'emergency_reports.user_id')
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'emergency_reports.tourism_info_id')
            ->where('emergency_reports.id', $id)
            ->first();

        return view('emergency.show', compact('emergencyReport'));
    }

    public function edit($id)
    {
        $emergencyReport = EmergencyReport::select(
            'emergency_reports.id',
            'us.name as user_name',
            'ti.name as tourism_name',
            'emergency_reports.title',
            'emergency_reports.status',
            'emergency_reports.created_at',
            'emergency_reports.description'
        )
            ->leftJoin('users as us', 'us.id', '=', 'emergency_reports.user_id')
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'emergency_reports.tourism_info_id')
            ->where('emergency_reports.id', $id)
            ->first();

        return view('emergency.edit', compact('emergencyReport'));
    }

    public function update($id, Request $request)
    {
        $emergencyReport = EmergencyReport::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->respond == 2) {
                $note = ' Dalam Penanganan ';
            } elseif ($request->respond == 3) {
                $note = ' Diselesaikan ';
            }
            $emergencyReport->status = $request->respond;
            $emergencyReport->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        $this->userLog('Keadaan Darurat ' . $emergencyReport->title . $note . Auth::user()->name);
        Alert::alert('Success', 'Keadaan Darurat Berhasil Ditanggapi', 'success');
        return redirect()->route('report-emergency.index');
    }
}
