<?php

namespace App\Http\Controllers\Ticket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use Lang, Auth, DB, Exception,Storage, Laratrust, DataTables, Alert;

class ReportController extends Controller
{
    public function reportUser(Request $request)
    {        
        if (!Laratrust::isAbleTo('view-report-ticket-user')) return abort(404);
        if($request->year == NULL && $request->month == NULL){
            $monthReport = date('m');
            $yearReport = date('Y');
        }else{
            $monthReport = $request->month;
            $yearReport = $request->year;
        }
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$monthReport, $yearReport);
        for ($day = 1; $day <= $dayInMonth; $day++) {
            $visitorRevenueTourisms[] = Ticket::select(DB::raw('ifnull(count(id),0) as count_visitor, ifnull(sum(price),0) as sum_price'))->whereDay('created_at','=',$day)->whereMonth('created_at', '=', $monthReport)->whereYear('created_at', '=', $yearReport)->whereStatus(1)->where('tourism_info_id',Auth::user()->tourism_info_id)->first();
            
        }
   
        return view('ticket.report.report-user', compact('visitorRevenueTourisms','monthReport','yearReport'));
    }

    public function reportAdministratorMonthly(Request $request)
    {
        if (!Laratrust::isAbleTo('view-report-ticket-administrator')) return abort(404);
        if($request->year == NULL && $request->month == NULL){
            $monthReport = date('m');
            $yearReport = date('Y');
        }else{
            $monthReport = $request->month;
            $yearReport = $request->year;
        }
        
        $visitorRevenueTourisms = TourismInfo::select('tourism_infos.id','tourism_infos.name as tourism_name',DB::raw('ifnull(count(ti.id),0) as count_visitor, ifnull(sum(ti.price),0) as sum_price'))
        ->leftjoin('tickets as ti', function($join) use($monthReport,$yearReport) {
            $join->on('ti.tourism_info_id', '=', 'tourism_infos.id');
            $join->whereMonth('ti.created_at', '=', $monthReport)->whereYear('ti.created_at', '=', $yearReport)->where('ti.status',1);
        })        
        ->groupby('tourism_infos.id','tourism_infos.name')->get();
        return view('ticket.report.report-administrator', compact('visitorRevenueTourisms','monthReport','yearReport'));
    }

    public function reportAdministratorDaily(Request $request)
    {
        if (!Laratrust::isAbleTo('view-report-ticket-administrator')) return abort(404);
        if($request->start_date == NULL && $request->end_date == NULL){
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
            $visitorRevenueDailys = TourismInfo::select('tourism_infos.id','tourism_infos.name as tourism_name',DB::raw('ifnull(count(ti.id),0) as count_visitor, ifnull(sum(ti.price),0) as sum_price'))
                ->leftjoin('tickets as ti', function($join) use($startDate,$endDate) {
                $join->on('ti.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('ti.created_at','>=',$startDate.' 00:00:00');
                $join->where('ti.created_at','<=',$endDate.' 23:59:59');
                $join->where('ti.status',1);
            })        
            ->groupby('tourism_infos.id','tourism_infos.name')->get();
            
        }else{            
            $startDate = $this->date_php_to_mysql($request->start_date);
            $endDate = $this->date_php_to_mysql($request->end_date);
            //$tourism = $request->tourism;

            $visitorRevenueDailys = TourismInfo::select('tourism_infos.id','tourism_infos.name as tourism_name',DB::raw('ifnull(count(ti.id),0) as count_visitor, ifnull(sum(ti.price),0) as sum_price'))
                ->leftjoin('tickets as ti', function($join) use($startDate,$endDate) {
                $join->on('ti.tourism_info_id', '=', 'tourism_infos.id');
                $join->where('ti.created_at','>=',$startDate.' 00:00:00');
                $join->where('ti.created_at','<=',$endDate.' 23:59:59');
                $join->where('ti.status',1);
            })        
            ->groupby('tourism_infos.id','tourism_infos.name')->get();

           /* $visitorRevenueDaily = TourismInfo::select('ti.created_at as ticket_date',DB::raw('ifnull(count(ti.id),0) as count_visitor, ifnull(sum(ti.price),0) as sum_price'))
            ->leftJoin('tickets as ti','ti.tourism_info_id','=','tourism_infos.id')
            //->where('ti.tourism_info_id',$tourism)
            ->whereBetWeen('ti.created_at',[$startDate, $endDate])
            ->groupby('tourism_infos.id','tourism_infos.name','ti.created_at')->get();*/
        }
        return view('ticket.report.report-administrator-daily', compact('startDate','endDate','visitorRevenueDailys'));
    }
}
