<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;

class TicketPromotion extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',

    ];
}
