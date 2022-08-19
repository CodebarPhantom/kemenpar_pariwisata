<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Emergency\EmergencyReport;
use App\Models\Ticket\Ticket;
use App\Models\Tourism\TourismInfo;
use Lang, Auth, DB, Exception, Storage, Laratrust, DataTables, Alert;

class DashboardController extends Controller
{
    public function index()
    {
        if (Laratrust::isAbleTo('view-dashboard-administrator')) {
            return redirect()->route('dashboard.administrator');
        } elseif (Laratrust::isAbleTo('view-dashboard-user')) {
            return redirect()->route('ticket.index');
        }
    }

    public function dashboardAdministrator(Request $request)
    {
        if ($request->year == null && $request->month == null) {
            $monthReport = date('m');
            $yearReport = date('Y');
        } else {
            $monthReport = $request->month;
            $yearReport = $request->year;
        }

        $emergencyReports = EmergencyReport::select(
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
            ->where('emergency_reports.status', 1)
            ->orderBy('emergency_reports.created_at', 'DESC')
            ->limit(3);


        if (!Laratrust::hasRole('superadmin')) {
            $emergencyReports = $emergencyReports->where(
                'ti.id',
                auth()->user()->tourism_info_id
            );
        }

        /* $visitorRevenueTourisms = Ticket::select('ti.name as tourism_name',DB::raw('count(tickets.id) as count_visitor, ifnull(sum(tickets.price),0) as sum_price'))
        ->leftJoin('tourism_infos as ti','ti.id','=','tickets.tourism_info_id')
        ->where('tickets.status',1)
        ->whereMonth('tickets.created_at', '=', $monthReport)->whereYear('tickets.created_at', '=', $yearReport)
        ->groupBy('ti.id','ti.name')->get();*/
        $visitorRevenueTourisms = TourismInfo::select('tourism_infos.id', 'tourism_infos.name as tourism_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($monthReport, $yearReport) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join
                    ->whereMonth('t.created_at', '=', $monthReport)
                    ->whereYear('t.created_at', '=', $yearReport)
                    ->where('t.status', 1);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            })
            ->where('tourism_infos.is_active', 1);

        $visitorVoidRevenueTourisms = TourismInfo::select('tourism_infos.id', 'tourism_infos.name as tourism_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($monthReport, $yearReport) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join
                    ->whereMonth('t.created_at', '=', $monthReport)
                    ->whereYear('t.created_at', '=', $yearReport)
                    ->where('t.status', 0);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            })
            ->where('tourism_infos.is_active', 1);

        if (!Laratrust::hasRole('superadmin')) {
            $visitorRevenueTourisms = $visitorRevenueTourisms->where(
                'tourism_infos.id',
                auth()->user()->tourism_info_id
            );
        }

        $visitorRevenueTourisms = $visitorRevenueTourisms->groupby('tourism_infos.id', 'tourism_infos.name')->get();
        $visitorVoidRevenueTourisms = $visitorVoidRevenueTourisms->groupby('tourism_infos.id', 'tourism_infos.name')->get();

        return view('dashboard.administrator',
            compact('visitorRevenueTourisms', 'visitorVoidRevenueTourisms', 'monthReport', 'yearReport', 'emergencyReports')
        );
    }

    public function dashboardUser(Request $request)
    {
        if ($request->year == null && $request->month == null) {
            $monthReport = date('m');
            $yearReport = date('Y');
        } else {
            $monthReport = $request->month;
            $yearReport = $request->year;
        }
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN, $monthReport, $yearReport);
        for ($day = 1; $day <= $dayInMonth; $day++) {
            $visitorRevenueTourisms[] = Ticket::select(
                DB::raw('count(id) as count_visitor, ifnull(sum(price),0) as sum_price')
            )
                ->whereDay('created_at', '=', $day)
                ->whereMonth('created_at', '=', $monthReport)
                ->whereYear('created_at', '=', $yearReport)
                ->whereStatus(1)
                ->where('tourism_info_id', Auth::user()->tourism_info_id)
                ->first();
        }

        return view('dashboard.user', compact('visitorRevenueTourisms', 'monthReport', 'yearReport'));
    }
}
