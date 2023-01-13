<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikeHateFavorite extends Model
{
    protected $fillable = ['user_id', 'pokemon_id', 'like', 'hate', 'favorite',];

    public function users()
    {
        return $this->belongsTo('App\User');
    }

}
