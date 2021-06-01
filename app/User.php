<?php

namespace App;

use App\Models\Ticket\PrimaryTests;
use App\Models\Ticket\SecondaryTests;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;


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
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
        return('#'); //kosong ga nampilin tombol profile
    }

    public function adminlte_desc()
    {
        $description = User::select('users.id','users.user_type','ti.name as tourism_name')->leftJoin('tourism_infos as ti','ti.id','=','users.tourism_info_id')->where('users.id',Auth::user()->id)->first();
        if($description->user_type == 1){
            $typeUser = 'Administrator';
        }else{
            $typeUser = 'User';
        }
        return $typeUser.' - '.$description->tourism_name;
    }

    public function adminlte_image()
    {
        //for this feature, you will need to add an extra function named adminlte_image() inside the User model, usually located on the app/User.php file. The recommend image size is: 160x160px.
        return(Auth::user()->url_photo);
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
