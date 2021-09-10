<?php

namespace App\Http\Controllers\Ticket;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Models\Promotion\TicketPromotion;
use App\Models\Tourism\TourismInfo;
use Lang, Auth, DB, Exception,Storage, Laratrust, DataTables, Alert;


class TicketController extends Controller
{
    public function index()
    {
        if (!Laratrust::isAbleTo('view-ticket')) return abort(404);
        return view('ticket.index');
    }

    public function store(Request $request)
    {
        if (!Laratrust::isAbleTo('view-ticket')) return abort(404);

        $this->validate($request, [
            'qty' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $ticketTotalPrice = 0;
            $discPrice = 0;
            $ticketPromotionId = NULL;

            $ticketPrice = TourismInfo::select('id','name','price')->whereId(Auth::user()->tourism_info_id)->first()->price;
            $ticketPromotion = TicketPromotion::select('id','name','tourism_info_id','disc_percentage')
            ->where('tourism_info_id',Auth::user()->tourism_info_id)
            ->where('end_date','>',date('Y-m-d H:i'))
            ->orderBy('end_date','desc')->first();

            if($ticketPromotion != NULL){
                $discPrice = ($ticketPromotion->disc_percentage/100)*$ticketPrice;
                $ticketPromotionId = $ticketPromotion->id;
            }
            $finalPrice = $ticketPrice-$discPrice;

            for($i=0; $i<$request->qty; $i++)
            {
                $ticket = new Ticket;
                $ticket->code = $this->setCodeTicket();
                $ticket->user_id = Auth::user()->id;
                $ticket->ticket_promotion_id = $ticketPromotionId;
                $ticket->tourism_info_id = Auth::user()->tourism_info_id;
                $ticket->price = $finalPrice;
                $ticket->status = 1;
                $ticket->save();
                $ticketCodePrint[] = $ticket->code;
                $ticketTotalPrice += $finalPrice;
                $this->userLog('Membuat Tiket Masuk '.$this->setCodeTicket());

            }


        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();

        $tourismInfo = TourismInfo::whereId(Auth::user()->tourism_info_id)->first();

        $ticketShowPrints = Ticket::select('tickets.id','tickets.code','tickets.price','ti.name as tourism_name','ti.url_logo','ti.manage_by','ti.insurance')
                           ->leftJoin('tourism_infos as ti','ti.id','=','tickets.tourism_info_id')
                           ->whereIn('tickets.code',$ticketCodePrint)
                           ->get();
        return view('ticket.print',compact('ticketShowPrints','tourismInfo','ticketTotalPrice','ticketPromotion'));


    }

    public function ticketData()
    {
        if (!Laratrust::isAbleTo('view-ticket')) return abort(404);

        $tickets = Ticket::select('id','code','status','price','created_at')->where('tourism_info_id',Auth::user()->tourism_info_id)/*->where('user_id',Auth::user()->id)->whereDay('created_at', '=', date('d'))*/->orderBy('created_at','DESC')->orderBy('id','DESC');
        return DataTables::of($tickets)
            ->editColumn('price',function($ticket){
                return number_format($ticket->price);
            })
            ->editColumn('status',function($ticket){
                if($ticket->status == 0){
                    $color = 'danger'; $status = 'Void';
                }elseif($ticket->status == 1){
                    $color = 'success'; $status = 'Paid';
                }

                return '<span class="badge bg-'.$color.'">'.Lang::get($status).'</span>';
            })
            ->editColumn('action',function($ticket){
                if($ticket->status == 1){
                    $icon = 'trash'; $color = 'danger';
                }elseif($ticket->status == 0){
                    $icon = 'redo'; $color = 'success';
                }

                $void =  '<form action="'.route('ticket.update',$ticket->id).'" method="POST" onsubmit="return confirm('.'\'Apakah ingin di proses?\''.');" ><input type="hidden" name="_method" value="PUT"> <input type="hidden" name="_token" value="'.csrf_token().'"> <button type="submit" class="btn btn-'.$color.' btn-flat btn-xs void" title="'.Lang::get('Void').'"><i class="fa fa-'.$icon.' fa-sm"></i></button></form>';
                return $void;
            })
            ->filterColumn('code', function ($query, $keyword) {
                $query->whereRaw("code like ?", ["%$keyword%"]);
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    public function update($id, Request $request)
    {
        if (!Laratrust::isAbleTo('view-ticket')) return abort(404);
        $ticket = Ticket::findOrFail($id);

        DB::beginTransaction();
        try {
            if($ticket->status == 0){
                $status = 1;
                $this->userLog('Batal Void Tiket Masuk '.$ticket->code);

            }elseif($ticket->status == 1){
                $status = 0;
                $this->userLog('Memvoid Tiket Masuk '.$ticket->code);

            }
            $ticket->status =  $status;
            $ticket->save();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return abort(500);
        }
        DB::commit();
        Alert::alert('Success', 'User '.$ticket->code.' Telah di Void', 'info');
        return redirect()->route('ticket.index');


    }

    private function setCodeTicket()
    {
        $codeTicket = TourismInfo::select('id','name','code')->whereId(Auth::user()->tourism_info_id)->first()->code;
        $check = Ticket::select(DB::raw('count(created_at) as count_ticket'))->whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->where('tourism_info_id',Auth::user()->tourism_info_id)->first()->count_ticket;
        if($check > 0){
            $number = ((int)$check)+1;
        }else{
            $number = 1;
        }
        $identityCode = $codeTicket;
        $uniquecode = str_pad($number,4,"0",STR_PAD_LEFT);
        return  $identityCode.date('m').date('Y').$uniquecode;
    }
}
