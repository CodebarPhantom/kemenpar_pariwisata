<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Test\PrimaryTests;
use App\Models\Test\SecondaryTests;
use App\Models\Ticket\Ticket;
use App\Models\Tourism\TourismInfo;
use App\Models\User\UserActivityLog;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'raw_password'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_profile_url()
    {
        // The return value should be a string, not a route or url.
        return '#'; //kosong ga nampilin tombol profile
    }

    public function adminlte_desc()
    {
        $description = User::select('users.id', 'users.user_type', 'ti.name as tourism_name')
            ->leftJoin('tourism_infos as ti', 'ti.id', '=', 'users.tourism_info_id')
            ->where('users.id', Auth::user()->id)
            ->first();
        if ($description->user_type == 1) {
            $typeUser = 'Administrator';
        } else {
            $typeUser = 'User';
        }
        return $typeUser . ' - ' . $description->tourism_name;
    }

    public function adminlte_image()
    {
        //for this feature, you will need to add an extra function named adminlte_image() inside the User model, usually located on the app/User.php file. The recommend image size is: 160x160px.
        return Auth::user()->url_photo;
    }

    public function user_activity_logs()
    {
        return $this->hasMany(UserActivityLog::class);
    }

    public function tourism_info()
    {
        return $this->belongsTo(TourismInfo::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function primary_tests()
    {
        return $this->hasMany(PrimaryTests::class);
    }

    public function secondary_tests()
    {
        return $this->hasManyThrough(SecondaryTests::class, PrimaryTests::class);
    }
}
