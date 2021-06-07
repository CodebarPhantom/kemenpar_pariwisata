<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class SecondaryTests extends Model
{
    protected $casts = [
        'price' => 'float',
    ];

    public function primary_tests()
    {
        return $this->belongsTo(PrimaryTests::class);
    }
}
