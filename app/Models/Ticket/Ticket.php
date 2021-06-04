<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Promotion\TicketPromotion;
use App\Models\Tourism\TourismInfo;

class Ticket extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(TicketItems::class);
    }

    public function tourism_info()
    {
        return $this->belongsTo(TourismInfo::class);
    }

    public function promotion()
    {
        return $this->belongsTo(TicketPromotion::class, 'ticket_promotion_id', 'id');
    }
}
