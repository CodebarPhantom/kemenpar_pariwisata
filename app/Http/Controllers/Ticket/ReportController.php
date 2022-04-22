<?php

namespace App\Http\Controllers\Ticket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laratrust;

class ReportController extends Controller
{
    public function reportUser(Request $request)
    {
        if (!Laratrust::isAbleTo('view-report-ticket-user')) {
            return abort(404);
        }
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
                DB::raw('ifnull(count(id),0) as count_visitor, ifnull(sum(price),0) as sum_price')
            )
                ->whereDay('created_at', '=', $day)
                ->whereMonth('created_at', '=', $monthReport)
                ->whereYear('created_at', '=', $yearReport)
                ->whereStatus(1)
                ->where('tourism_info_id', Auth::user()->tourism_info_id)
                ->first();
        }

        return view('ticket.report.report-user', compact('visitorRevenueTourisms', 'monthReport', 'yearReport'));
    }

    public function reportAdministratorMonthly(Request $request)
    {
        if (!Laratrust::isAbleTo('view-report-ticket-administrator')) {
            return abort(404);
        }
        if ($request->year == null && $request->month == null) {
            $monthReport = date('m');
            $yearReport = date('Y');
        } else {
            $monthReport = $request->month;
            $yearReport = $request->year;
        }

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
            });

        if (!Laratrust::hasRole('superadmin')) {
            $visitorRevenueTourisms = $visitorRevenueTourisms->where(
                'tourism_infos.id',
                auth()->user()->tourism_info_id
            );
        }

        $visitorRevenueTourisms = $visitorRevenueTourisms->groupby('tourism_infos.id', 'tourism_infos.name')->get();

        return view(
            'ticket.report.report-administrator',
            compact('visitorRevenueTourisms', 'monthReport', 'yearReport')
        );
    }

    public function reportAdministratorDaily(Request $request)
    {
        if (!Laratrust::isAbleTo('view-report-ticket-administrator')) {
            return abort(404);
        }

        if ($request->start_date == null && $request->end_date == null) {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        } else {
            $startDate = $this->date_php_to_mysql($request->start_date);
            $endDate = $this->date_php_to_mysql($request->end_date);
        }

        $visitorRevenueDailys = TourismInfo::select('tourism_infos.id', 'tourism_infos.name as tourism_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($startDate, $endDate) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('t.created_at', '>=', $startDate . ' 00:00:00');
                $join->where('t.created_at', '<=', $endDate . ' 23:59:59');
                $join->where('t.status', 1);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            });

        if (!Laratrust::hasRole('superadmin')) {
            $visitorRevenueDailys = $visitorRevenueDailys->where('tourism_infos.id', auth()->user()->tourism_info_id);
        }

        $visitorRevenueDailys = $visitorRevenueDailys->groupby('tourism_infos.id', 'tourism_infos.name')->get();

        return view(
            'ticket.report.report-administrator-daily',
            compact('startDate', 'endDate', 'visitorRevenueDailys')
        );
    }

    public function reportAdministratorDailyVoid(Request $request)
    {
        if (!Laratrust::isAbleTo('view-report-ticket-administrator')) {
            return abort(404);
        }

        if ($request->start_date == null && $request->end_date == null) {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        } else {
            $startDate = $this->date_php_to_mysql($request->start_date);
            $endDate = $this->date_php_to_mysql($request->end_date);
        }

        $visitorRevenueDailys = TourismInfo::select('tourism_infos.id', 'tourism_infos.name as tourism_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($startDate, $endDate) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('t.created_at', '>=', $startDate . ' 00:00:00');
                $join->where('t.created_at', '<=', $endDate . ' 23:59:59');
                $join->where('t.status', 0);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            });

        if (!Laratrust::hasRole('superadmin')) {
            $visitorRevenueDailys = $visitorRevenueDailys->where('tourism_infos.id', auth()->user()->tourism_info_id);
        }

        $visitorRevenueDailys = $visitorRevenueDailys->groupby('tourism_infos.id', 'tourism_infos.name')->get();

        return view('ticket.report.report-administrator-daily',
            compact('startDate', 'endDate', 'visitorRevenueDailys')
        );
    }
}
