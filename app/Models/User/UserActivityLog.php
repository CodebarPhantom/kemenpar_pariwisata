<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserActivityLog extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
