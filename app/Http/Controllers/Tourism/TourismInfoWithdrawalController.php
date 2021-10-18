<?php

namespace App\Http\Controllers\Tourism;

use Carbon\Carbon;
use App\Models\Tourism\TourismInfoBalance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tourism\TourismInfo;
use RealRashid\SweetAlert\Facades\Alert;
use App\Traits\AllSetLog;


use Str, DB, Lang, DataTables, Laratrust;

class TourismInfoWithdrawalController extends Controller
{
    use AllSetLog;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Laratrust::isAbleTo('view-withdrawal'))  return abort(404);  
        return view('tourism.withdrawal.index');
    }

    public function data()
    {
        $tourismWithdrawals = TourismInfoBalance::select('tourism_info_balances.*')->with(['tourism'])->withdrawal()
        ->when(Laratrust::hasRole('administrator'), function ($query) {
            $query->where('tourism_info_id',auth()->user()->tourism_info_id);
        })
        ->orderBy('tourism_info_balances.created_at','DESC');

        return DataTables::of($tourismWithdrawals)
            ->editColumn('name', function ($tourismWithdrawal) {
                return   $tourismWithdrawal->tourism->name;
            })

            ->editColumn('tourism_info_balances.created_at', function ($tourismWithdrawal) {
                return   $tourismWithdrawal->created_at->format('d M Y');
            })

            ->editColumn('status', function ($tourismWithdrawal) {
               
                return '<span class="badge bg-' . TourismInfoBalance::BALANCESTATUS[$tourismWithdrawal->status]['bs_color'] . ' align-middle">' . TourismInfoBalance::BALANCESTATUS[$tourismWithdrawal->status]['text'] . '</span>';
            })
            
            ->editColumn('amount', function ($tourismWithdrawal) {
                return 'Rp. '.number_format($tourismWithdrawal->amount);
            })

            ->editColumn('action', function ($tourismWithdrawal) {
                $reject = ''; $process = ''; $complete = '';

                if($tourismWithdrawal->status == TourismInfoBalance::BALANCESTATUS[2]['status']){
                    $reject =  '<a href="#" data-href="'.route('tourism-info-withdrawal.rejected').'" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="Tolak Pengajuan" data-toggle="modal" data-text="Apakah anda yakin untuk menolak pencairan dana '.$tourismWithdrawal->tourism->name.'" data-target="#modal-confirmation" data-value="'.$tourismWithdrawal->id.'"><i class="fa fa-times text-danger"></i></a>';
                    $process =  '<a href="#" data-href="'.route('tourism-info-withdrawal.processed').'" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="Proses Pengajuan" data-toggle="modal" data-text="Apakah anda yakin untuk memproses pencairan dana '.$tourismWithdrawal->tourism->name.'" data-target="#modal-confirmation" data-value="'.$tourismWithdrawal->id.'"><i class="fa fa-question text-warning"></i></a>';
                }

                if($tourismWithdrawal->status == TourismInfoBalance::BALANCESTATUS[3]['status']){
                    $complete = '<a href="#" data-href="'.route('tourism-info-withdrawal.completed').'" class="btn btn-sm btn-clean btn-icon btn-icon-md btn-tooltip" title="Proses Pengajuan" data-toggle="modal" data-text="Apakah anda yakin untuk menyelesaikan pencairan dana '.$tourismWithdrawal->tourism->name.'" data-target="#modal-confirmation" data-value="'.$tourismWithdrawal->id.'"><i class="fa fa-check text-success"></i></a>';

                }
                return $process . $reject . $complete;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);

    }

    public function processed(Request $request)
    {
        $tourismInfoBalance = TourismInfoBalance::with(['tourism'])->findOrFail($request->confirmation_id);
        $tourismInfoBalance->status = TourismInfoBalance::BALANCESTATUS[3]['status'];
        $tourismInfoBalance->save();       

        $tourismInfo = TourismInfo::slect('id','balance')->findOrFail($tourismInfoBalance->tourism_info_id);

        $this->tourismInfoLog($tourismInfoBalance->tourism_info_id, 'Pengajuan penarikan dana '.$tourismInfoBalance->tourism->name.' sebesar '. $tourismInfo->balance.' diproses, silahkan selesaikan pengajuan dengan konfirmasi pihak-pihak terkait', TourismInfoBalance::BALANCE, TourismInfoBalance::BALANCESTATUS[3]['status']);

        Alert::alert('Information', 'Pengajuan penarikan dana '.$tourismInfoBalance->tourism->name.' diproses, silahkan selesaikan pengajuan dengan konfirmasi pihak-pihak terkait', 'info');
        return redirect()->route('tourism-info-withdrawal.index');

    }

    public function rejected(Request $request)
    {
        $tourismInfoBalance = TourismInfoBalance::with(['tourism'])->findOrFail($request->confirmation_id);
        $tourismInfoBalance->status = TourismInfoBalance::BALANCESTATUS[4]['status'];
        $tourismInfoBalance->save();       

        Alert::alert('Information', 'Pengajuan penarikan dana '.$tourismInfoBalance->tourism->name.' ditolak', 'warning');
        return redirect()->route('tourism-info-withdrawal.index');

    }

    public function completed(Request $request)
    {
        $tourismInfoBalance = TourismInfoBalance::with(['tourism'])->findOrFail($request->confirmation_id);
        $tourismInfoBalance->status = TourismInfoBalance::BALANCESTATUS[5]['status'];
        $tourismInfoBalance->save();    
        
        $tourismInfo = TourismInfo::select('id','balance')->findOrFail($tourismInfoBalance->tourism_info_id);
        $tourismInfo->balance -= $tourismInfoBalance->amount;
        $tourismInfo->save();
        

        Alert::alert('Information', 'Pengajuan penarikan dana '.$tourismInfoBalance->tourism->name.' sudah selesai', 'succeess');
        return redirect()->route('tourism-info-withdrawal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $tourismInfo = TourismInfo::select('id','balance')->findOrFail(auth()->user()->tourism_info_id);

        $tourisminfoBalanceCheck = TourismInfoBalance::whereNotIn('status',[4,5])->where('tourism_info_id',auth()->user()->tourism_info_id)->first();
        
        if($tourisminfoBalanceCheck != NULL){
            Alert::alert('Information', 'Anda sudah mengajukan penarikan dana, Silahkan selesaikan pengajauan sebelumnya terlebih dahulu', 'error');
            return redirect()->route('tourism-info-withdrawal.index'); 
        }else{

            if($tourismInfo->balance == 0) {
                Alert::alert('Information', 'Saldo anda 0', 'error');
                return redirect()->route('tourism-info-withdrawal.index'); 
            }

            $tourismInfoBalance = new TourismInfoBalance();
            $tourismInfoBalance->tourism_info_id =  $request->confirmation_id;
            $tourismInfoBalance->status = TourismInfoBalance::BALANCESTATUS[2]['status'];
            $tourismInfoBalance->amount =  $tourismInfo->balance;
            $tourismInfoBalance->save();    
        
        

            $this->tourismInfoLog($tourismInfoBalance->tourism_info_id, 'Penarikan dana '.$tourismInfoBalance->tourism->name.' sebesar '. $tourismInfo->balance.' berhasil diajukan.', TourismInfoBalance::BALANCE, TourismInfoBalance::BALANCESTATUS[2]['status']);

            Alert::alert('Information', 'Penarikan dana '.$tourismInfoBalance->tourism->name.' sebesar '. $tourismInfo->balance.' berhasil diajukan.', 'info');
            return redirect()->route('tourism-info-withdrawal.index'); 
        }


        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TourismInfoBalance  $tourismInfoBalance
     * @return \Illuminate\Http\Response
     */
    public function show(TourismInfoBalance $tourismInfoBalance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TourismInfoBalance  $tourismInfoBalance
     * @return \Illuminate\Http\Response
     */
    public function edit(TourismInfoBalance $tourismInfoBalance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TourismInfoBalance  $tourismInfoBalance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TourismInfoBalance $tourismInfoBalance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TourismInfoBalance  $tourismInfoBalance
     * @return \Illuminate\Http\Response
     */
    public function destroy(TourismInfoBalance $tourismInfoBalance)
    {
        //
    }
}
