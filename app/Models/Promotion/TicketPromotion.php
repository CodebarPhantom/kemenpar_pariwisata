<?php

namespace App\Models\Promotion;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tourism\TourismInfo;
use App\Models\Ticket\Ticket;

class TicketPromotion extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function tourism_info()
    {
        return $this->belongsTo(TourismInfo::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_promotion_id', 'id');
    }
}
