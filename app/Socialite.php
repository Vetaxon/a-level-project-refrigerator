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


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * @param $social
     * @param $userSocial
     * @return mixed
     */
    public static function getUserBySocials($social, $userSocial)
    {
        return self::where([
            'provider' => $social,
            'provider_id' => $userSocial->getId()])
            ->first();
    }
}
