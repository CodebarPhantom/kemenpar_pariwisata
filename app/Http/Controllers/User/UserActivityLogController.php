<?php

namespace App\Http\Controllers\User;


use App\User;
use Carbon\Carbon;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use App\Models\User\UserActivityLog;
use Auth, DB, View, DataTables, Alert, Lang, Laratrust, Storage;

class UserActivityLogController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-user-log')) return abort(404);

        return view('user.user-log.index');
    }

    public function dataLog()
    {
        if (!Laratrust::isAbleTo('view-user-log')) return abort(404);

        $logActivities = UserActivityLog::select('user_activity_logs.id','user_activity_logs.information','us.name as username','user_activity_logs.created_at')
                        ->leftJoin('users as us','us.id','=','user_activity_logs.user_id')
                        ->orderBy('user_activity_logs.created_at','DESC');

        if (!Laratrust::hasRole('superadmin')) {
            $logActivities = $logActivities->where('us.tourism_info_id', auth()->user()->tourism_info_id);
        }

        return DataTables::of($logActivities)
            ->editColumn('us.name', function($logActivities) {
                return $logActivities->username;
            })
            ->editColumn('created_at', function($logActivities) {
                return $logActivities->created_at ? with(new Carbon($logActivities->created_at))->format('D, d-m-Y H:i') : '';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(user_activity_logs.created_at,'%d-%m-%Y %H:%i') like ?", ["%$keyword%"]);
            })
            ->make(true);
    }
}
