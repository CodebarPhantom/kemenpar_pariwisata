<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Model;

class PrimaryTests extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function secondary_tests()
    {
        return $this->hasMany(SecondaryTests::class);
    }
}
