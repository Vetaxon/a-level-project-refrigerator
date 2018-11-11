<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 11.11.2018
 * Time: 15:52
 */

namespace App\Services;

use App\Mail\RegisterMail;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserServices
{

    public function createUserFromDashboard($request)
    {
        $userParams = $request->only(['name', 'email']);

        $password = str_random(6);

        $userParams['password'] = bcrypt($password);

        $user = User::create($userParams);

        Mail::to($user)->queue(new RegisterMail($user, $this->getMessageEmail(), $password));

        return $user;
    }

    public function createUserThrowSocialite($userSocial)
    {
        $userParams = [
            'email' => $userSocial->getEmail(),
            'name' => $userSocial->getName() ? $userSocial->getName() : $userSocial->getNickname(),
            'password' => bcrypt(str_random(70)),
        ];

        $user = User::create($userParams);

        Mail::to($user)->queue(new RegisterMail($user, $this->getMessageEmail()));

        return $user;
    }

    /**
     * @param $user
     * @param $social
     * @param $userSocial
     */
    public function checkUserSocial($user, $social, $userSocial)
    {
        $userProviders = $user->socialites()->pluck('provider')->toArray();

        if(!in_array($social, $userProviders)){
            $this->createUserSocialite($user,  $userSocial->getId(), $social);
        }
    }

    /**
     * @param $user
     * @param $userSocialId
     * @param $social
     * @return mixed
     */
    protected function createUserSocialite($user, $userSocialId, $social)
    {
        return $user->socialites()->create([
            'provider' => $social,
            'provider_id' => $userSocialId
        ]);
    }

    /**
     * @return string
     */
    protected function getMessageEmail():string
    {
        return  "Congratulations on your registration in \"refrigerator\" project. To obtain moderator rights, ask them from the administrator.";

    }


}