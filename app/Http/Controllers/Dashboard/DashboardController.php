<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ticket\Ticket;
use App\Models\Tourism\TourismInfo;
use Lang, Auth, DB, Exception,Storage, Laratrust, DataTables, Alert;


class DashboardController extends Controller
{
    
    public function index()
    {
        if(Laratrust::isAbleTo('view-dashboard-administrator')){
            return redirect()->route('dashboard.administrator');
        }elseif(Laratrust::isAbleTo('view-dashboard-user')){
            return redirect()->route('dashboard.user');
        };

    }
    
    public function dashboardAdministrator(Request $request)
    {
        if($request->year == NULL && $request->month == NULL){
            $monthReport = date('m');
            $yearReport = date('Y');
        }else{
            $monthReport = $request->month;
            $yearReport = $request->year;
        }

       /* $visitorRevenueTourisms = Ticket::select('ti.name as tourism_name',DB::raw('count(tickets.id) as count_visitor, ifnull(sum(tickets.price),0) as sum_price'))
        ->leftJoin('tourism_infos as ti','ti.id','=','tickets.tourism_info_id')     
        ->where('tickets.status',1)
        ->whereMonth('tickets.created_at', '=', $monthReport)->whereYear('tickets.created_at', '=', $yearReport)
        ->groupBy('ti.id','ti.name')->get();*/
        $visitorRevenueTourisms = TourismInfo::select('tourism_infos.id','tourism_infos.name as tourism_name',DB::raw('ifnull(count(ti.id),0) as count_visitor, ifnull(sum(ti.price),0) as sum_price'))
        ->leftjoin('tickets as ti', function($join) use($monthReport,$yearReport) {
            $join->on('ti.tourism_info_id', '=', 'tourism_infos.id');
            $join->whereMonth('ti.created_at', '=', $monthReport)->whereYear('ti.created_at', '=', $yearReport)->where('ti.status',1);
        })        
        ->groupby('tourism_infos.id','tourism_infos.name')->get();
        return view('dashboard.administrator',compact('visitorRevenueTourisms','monthReport','yearReport'));
    }



    public function dashboardUser(Request $request)
    {
        if($request->year == NULL && $request->month == NULL){
            $monthReport = date('m');
            $yearReport = date('Y');
        }else{
            $monthReport = $request->month;
            $yearReport = $request->year;
        }
        $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$monthReport, $yearReport);
        for ($day = 1; $day <= $dayInMonth; $day++) {
            $visitorRevenueTourisms[] = Ticket::select(DB::raw('count(id) as count_visitor, ifnull(sum(price),0) as sum_price'))->whereDay('created_at','=',$day)->whereMonth('created_at', '=', $monthReport)->whereYear('created_at', '=', $yearReport)->whereStatus(1)->where('tourism_info_id',Auth::user()->tourism_info_id)->first();
            
        }

        return view('dashboard.user',compact('visitorRevenueTourisms','monthReport','yearReport'));
    }
}
