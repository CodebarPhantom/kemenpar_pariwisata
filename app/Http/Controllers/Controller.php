<?php

namespace App\Http\Controllers;

use DB, Alert, Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User\UserActivityLog;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validationError($message)
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        Alert::alert('Gagal',$message, 'error');
        return redirect()->back()->withInput(request()->except('_token'));
    }

    public function userLog($information)
    {
        $userLog = new UserActivityLog;
        $userLog->user_id = Auth::user()->id;
        $userLog->information = $information;
        $userLog->save();
        return;
    }

    function date_php_to_mysql($tgl) {
        //put your code here
        $tahun = substr($tgl,6,4);
        $bulan = substr($tgl,3,2);
        $tanggal = substr($tgl,0,2);
        return $tahun.'-'.$bulan.'-'.$tanggal;
    }

}
