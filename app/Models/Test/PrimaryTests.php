<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use App\User;

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
