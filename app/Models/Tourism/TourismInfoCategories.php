<?php

namespace App\Models\Tourism;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket\TicketItems;

class TourismInfoCategories extends Model
{
    protected $casts = [
        'price' => 'float',
    ];

    public function tourism_info()
    {
        return $this->belongsTo(TourismInfo::class);
    }

    public function ticket_items()
    {
        return $this->hasMany(TicketItems::class);
    }
}
