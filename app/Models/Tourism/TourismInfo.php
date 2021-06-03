<?php

namespace App\Models\Tourism;

use Illuminate\Database\Eloquent\Model;

class TourismInfo extends Model
{
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
