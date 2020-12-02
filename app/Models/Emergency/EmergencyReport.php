<?php

namespace App\Models\Emergency;

use Illuminate\Database\Eloquent\Model;

class EmergencyReport extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
