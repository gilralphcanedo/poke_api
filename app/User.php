<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'birthdate', 'password',
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

    public function likes(){
        return $this->hasMany('\App\LikeHateFavorite')->where('like', 1);
    }

    public function hates(){
        return $this->hasMany('\App\LikeHateFavorite')->where('hate', 1);
    }
    public function favorites(){
        return $this->hasMany('\App\LikeHateFavorite')->where('favorite', 1);
    }

    public function likeHateFavorites(){
        return $this->hasMany('\App\LikeHateFavorite');
    }

}
