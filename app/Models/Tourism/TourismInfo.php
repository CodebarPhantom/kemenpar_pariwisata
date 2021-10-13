<?php

namespace App\Models\Tourism;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Tourism\TourismInfoCategories;
use App\Models\Ticket\Ticket;
use App\Models\Promotion\TicketPromotion;
use App\Models\Setting\Amenity;
use App\Models\Tourism\TourismInfoAmenity;
use App\Models\Tourism\TourismInfoGallery;
use Str;

class TourismInfo extends Model
{
    protected $casts = [
        'price' => 'float',
        'count_visitor' => 'integer',
        'sum_price' => 'float',
        'opening_hour'=>'array',
        'open_weather'=>'array',
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
       return $this->hasMany(TourismInfoAmenity::class)->select(
            'tourism_info_amenities.id',
            'tourism_info_amenities.tourism_info_id',
            'tourism_info_amenities.amenity_id',
            'amenity.icon',
            'amenity.icon_class',
            'amenity.name',
            'amenity.category'
        )->leftJoin('amenities as amenity','amenity.id','=','tourism_info_amenities.amenity_id');
    }

    public function galleries(){
        return $this->hasMany(TourismInfoGallery::class);
        
    }

    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = Str::ucfirst($value);
    }
}
