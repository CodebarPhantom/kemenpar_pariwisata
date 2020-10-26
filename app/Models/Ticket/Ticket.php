<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $casts = [
        'created_at' => 'datetime',
    ];
}
