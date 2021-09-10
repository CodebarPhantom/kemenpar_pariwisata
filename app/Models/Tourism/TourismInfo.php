<?php

namespace App\Models\Tourism;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Tourism\TourismInfoCategories;
use App\Models\Ticket\Ticket;
use App\Models\Promotion\TicketPromotion;
use App\Models\Setting\Amenity;

class TourismInfo extends Model
{
    protected $casts = [
        'price' => 'float',
        'count_visitor' => 'integer',
        'sum_price' => 'float',
        'opening_hour'=>'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function categories()
    {
        return $this->hasMany(TourismInfoCategories::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function promotion()
    {
        return $this->hasMany(TicketPromotion::class);
    }

    public function amenities()
    {
        return $this->hasMany(Amenity::class);
    }
}
