<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class SecondaryTests extends Model
{
    public function primary_tests()
    {
        return $this->belongsTo(PrimaryTests::class);
    }
}
