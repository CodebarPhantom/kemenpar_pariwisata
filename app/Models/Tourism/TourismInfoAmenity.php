<?php

namespace App\Models\Tourism;

use App\Models\Setting\Amenity;
use Illuminate\Database\Eloquent\Model;

class TourismInfoAmenity extends Model
{
    public function detailAmenity()
    {
       return $this->hasOne(Amenity::class,'id','amenity_id');
    }
}
