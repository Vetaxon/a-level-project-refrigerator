<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Http\Requests\UpdateAuthRequest;
use App\Services\Contracts\MessageLogEvent;
use App\Services\UserServices;
use App\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Register and Get a JWT.
     *
     * @param RegisterRequest $request
     * @param UserServices $userServices
     * @param MessageLogEvent $event
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request, UserServices $userServices, MessageLogEvent $event)
    {
        $user = $userServices->createUserApi($request);
        
        $event->send(EventMessages::userRegistered($user));

        $credentials = collect($request)->only(['email', 'password'])->toArray();

        $token = auth()->attempt($credentials);

        return $this->respondWithToken($token, $user);
    }


    /**
     * Update authenticated user
     *
     * @param UpdateAuthRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateAuthRequest $request)
    {
        auth()->user()->update($request->all());

        return $this->user();
    }

    /**
     * Change password for authenticated user
     * @param UpdateAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function changePassword(UpdateAuthRequest $request)
    {
        auth()->user()->update(['password' => bcrypt($request->password)]);

        return response()->json(['message' => 'Password updated']);
    }


    /**Delete authenticated user
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy()
    {
        User::find(auth()->id())->delete();

        return response()->json(['message' => 'User has been deleted']);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if (!$token = auth()->attempt($request->only(['email', 'password']))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token, auth()->user());

    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(auth()->user());
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), $this->user());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}