<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket\Ticket;
use App\Models\Tourism\TourismInfoCategories;

class TicketItems extends Model
{
    protected $casts = [
        'price' => 'float',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function categories()
    {
        return $this->belongsTo(TourismInfoCategories::class, 'tourism_info_category_id', 'id');
    }
}
