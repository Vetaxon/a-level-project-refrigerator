<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socialite extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'provider', 'provider_id',
    ];


}
