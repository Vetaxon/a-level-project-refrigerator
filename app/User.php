<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable implements JWTSubject
{
    use LaratrustUserTrait;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function recipes()
    {
        return $this->hasMany('App\Recipe');
    }

    public function ingredients()
    {
        return $this->hasMany('App\Ingredient');
    }

    public function refrigeratorIngredients()
    {
        return $this->belongsToMany('App\Ingredient', 'refrigerators')
            ->withPivot('amount')
            ->select(['id', 'name', 'amount']);

    }

    public function refrigerators()
    {
        return $this->hasMany('App\Refrigerator');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialites()
    {
        return $this->hasMany('App\Socialite');
    }

    /**
     * @param $userSocial
     * @param $social
     * @return User
     */
    public static function createNewUserSocialite($userSocial, $social)
    {
        return self::create([
            'email' => $userSocial->getEmail(),
            'name' => $userSocial->getName() ? $userSocial->getName() : $userSocial->getNickname(),
            'password' => bcrypt(str_random(70)),
        ]);
    }

    /**
     * @param $user
     * @param $userSocial
     * @param $social
     * @return mixed
     */
    public static function createUserSocialite($user, $userSocial, $social)
    {
        return $user->socialites()->create([
            'provider' => $social,
            'provider_id' => $userSocial->getId()
        ]);
    }

    /**
     * @return User|\Illuminate\Database\Eloquent\Builder
     */
    public static function getAllUsersWithCounts()
    {
        return self::with(['ingredients' => function ($query) {
            return $query->selectRaw('user_id, count(*) as count')->groupBy('user_id');
        }])
            ->with(['recipes' => function ($query) {
                return $query->selectRaw('user_id, count(*) as count')->groupBy('user_id');
            }])
            ->with(['refrigerators' => function ($query) {
                return $query->selectRaw('user_id, count(*) as count')->groupBy('user_id');
            }])
            ->with(['socialites' => function ($query) {
                return $query->selectRaw('*');
            }]);
    }

}
