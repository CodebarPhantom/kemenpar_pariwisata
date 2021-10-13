<?php

namespace App\Traits;

use App\Models\Tourism\TourismInfoLog;

trait AllSetLog
{

    public function tourismInfoLog($tourismInfoId, $note, $category, $status)
    {
        $tourismInfoLog = new TourismInfoLog();
        $tourismInfoLog->toursim_info_id = $tourismInfoId;
        $tourismInfoLog->note = $note;
        $tourismInfoLog->category = $category; 
        $tourismInfoLog->status = $status;
        $tourismInfoLog->save(); 
    }
}