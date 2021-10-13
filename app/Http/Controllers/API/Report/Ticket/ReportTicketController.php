<?php

namespace App\Http\Controllers\API\Report\Ticket;

use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportTicketController extends Controller
{
    public function daily()
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d');

        $visitorRevenueDaily = TourismInfo::select('tourism_infos.name as tourism_name')
            ->selectRaw('ifnull( cat.NAME, "Umum" ) AS category_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($startDate, $endDate) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('t.created_at', '>=', $startDate . ' 00:00:00');
                $join->where('t.created_at', '<=', $endDate . ' 23:59:59');
                $join->where('t.status', 1);
                $join->where('t.is_qr',0);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            })
            ->where('tourism_infos.id', auth()->user()->tourism_info_id)
            ->groupby(
                'tourism_infos.id',
                'tourism_infos.name',
                'cat.id',
                'cat.name'
            )
            ->get();

        return response()->json(
            [
                'data' => $visitorRevenueDaily,
            ],
            200
        );
    }

    public function customDate(Request $request){

        $startDate = Carbon::parse($request->start_date)->format('Y-m-d');
        $endDate = Carbon::parse($request->end_date)->format('Y-m-d');
        $visitorRevenueDaily = TourismInfo::select('tourism_infos.name as tourism_name')
            ->selectRaw('ifnull( cat.NAME, "Umum" ) AS category_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($startDate, $endDate) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('t.created_at', '>=', $startDate . ' 00:00:00');
                $join->where('t.created_at', '<=', $endDate . ' 23:59:59');
                $join->where('t.status', 1);
                $join->where('t.is_qr',0);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            })
            ->where('tourism_infos.id', auth()->user()->tourism_info_id)
            ->groupby(
                'tourism_infos.id',
                'tourism_infos.name',
                'cat.id',
                'cat.name'
            )
            ->get();

        return response()->json(
            [
                'data' => $visitorRevenueDaily,
            ],
            200
        );
    }

    public function monthly()
    {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');

        $visitorRevenueMonthly = TourismInfo::select('tourism_infos.name as tourism_name')
            ->selectRaw('ifnull( cat.NAME, "Umum" ) AS category_name')
            ->selectRaw('ifnull(sum( ti.quantity ), 0) count_visitor')
            ->selectRaw('ifnull(sum( ti.quantity * ti.price ), 0) sum_price')
            ->leftJoin('tourism_info_categories as cat', 'cat.tourism_info_id', '=', 'tourism_infos.id')
            ->leftJoin('tickets as t', function ($join) use ($startDate, $endDate) {
                $join->on('t.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('t.created_at', '>=', $startDate . ' 00:00:00');
                $join->where('t.created_at', '<=', $endDate . ' 23:59:59');
                $join->where('t.status', 1);
                $join->where('t.is_qr',0);
            })
            ->leftJoin('ticket_items as ti', function ($join) {
                $join->on('ti.ticket_id', '=', 't.id');
                $join->on('ti.tourism_info_category_id', '=', 'cat.id');
            })
            ->where('tourism_infos.id', auth()->user()->tourism_info_id)
            ->groupby(
                'tourism_infos.id',
                'tourism_infos.name',
                'cat.id',
                'cat.name'
            )
            ->get();

        return response()->json(
            [
                'data' => $visitorRevenueMonthly,
            ],
            200
        );
    }
}
