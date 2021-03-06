<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Bestmomo\LaravelEmailConfirmation\Traits\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Handle Social login request
     *
     * @return response
     */

    public function socialLogin($social)
    {

        return Socialite::driver($social)->redirect();

    }

    /**
     * Obtain the user information from Social Logged in.
     * @param $social
     * @param UserServices $userServices
     * @return Response
     */

    public function handleProviderCallback($social, UserServices $userServices)
    {
        $userSocial = Socialite::driver($social)->user();

        $userFind = User::where('email', $userSocial->getEmail())->first();

        $user = $userFind ? $userFind : $userServices->createUserThrowSocialite($userSocial);

        $userServices->checkUserSocial($user, $social, $userSocial);

        $this->guard()->login($user, true);

        return redirect('home');
    }

}
