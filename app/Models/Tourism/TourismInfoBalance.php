<?php

namespace App\Models\Tourism;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tourism\TourismInfo;

class TourismInfoBalance extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public const BALANCE = 'BALANCE';

    public const BALANCESTATUS =
    array(
       
        0 => array('status'=>0, 'text'=>'Penambahan', 'bs_color'=>'danger'),
        1 => array('status'=>1, 'text'=>'Pengurangan', 'bs_color'=>'success'),
        2 => array('status'=>2, 'text'=>'Pengajuan Withdrawal', 'bs_color'=>'warning'),
        3 => array('status'=>3, 'text'=>'Proses Withdrawal', 'bs_color'=>'info'),
        4 => array('status'=>4, 'text'=>'Pengajuan Withdrawal Ditolak', 'bs_color'=>'danger'),
        5 => array('status'=>5, 'text'=>'Selesai', 'bs_color'=>'success'),




    );


    public function tourism()
    {
        return $this->belongsTo(TourismInfo::class,'tourism_info_id')->select('id','name','balance');

    }

    public function scopeWithdrawal($query)
    {
        return $query->whereNotIn('status',['0,1']);
    }
}
