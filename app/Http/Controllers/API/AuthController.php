<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UpdateRequest;
use App\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    /**
     * Register and Get a JWT.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if (!$user)
            return response()->json(['error' => 'Registration is failed'], 401);;

        $credentials = collect($request)->only(['email', 'password'])->toArray();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $user);
    }


    /**
     * Update authenticated user
     *
     * @param UpdateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateRequest $request)
    {
        if ($user = auth()->user()->fill($request->all())->save())
            return $this->user();

        return response()->json(['error' => 'Updating is failed'], 401);

    }

    /**
     * Change password for authenticated user
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function changePassword(UpdateRequest $request)
    {
        if ($user = auth()->user()->update(['password' => bcrypt($request->password)]))
            return response()->json(['message' => 'Password updated']);

        return response()->json(['error' => 'Updating is failed'], 401);
    }


    /**Delete authenticated user
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy()
    {
        if (User::find(auth()->user()->id)->delete())
            return response()->json(['message' => 'User has been deleted']);

        return response()->json(['error' => 'Deleting is failed'], 401);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token);
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
        return $this->respondWithToken(auth()->refresh());
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user = false)
    {
        if ($user) {
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}