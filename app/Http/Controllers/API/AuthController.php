<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UpdateAuthRequest;
use App\Mail\RegisterMail;
use App\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


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
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function register(RegisterRequest $request)
    {
        $message = 'Сongratulations on your registration in "refrigerator" project';
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            Mail::to($user)->queue(new RegisterMail($user, $message));
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        $credentials = collect($request)->only(['email', 'password'])->toArray();

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 422);
        }

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
        try {
            if ($user = auth()->user()->update($request->only(['name', 'email']))) {
                return $this->user();
            }
            return response()->json(['error' => 'Updating is failed'], 422);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    /**
     * Change password for authenticated user
     * @param UpdateAuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function changePassword(UpdateAuthRequest $request)
    {
        try {

            $user = auth()->user();

            if (Hash::check($request->old_password, $user->password)) {
                $user->update(['password' => bcrypt($request->password)]);
                return response()->json(['message' => 'Password has been updated']);
            }

            return response()->json(['error' => 'Invalid old password'], 422);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }


    /**Delete authenticated user
     * @return \Illuminate\Http\JsonResponse
     */

    public function destroy()
    {
        try {
            if (User::find(auth()->user()->id)->delete()) {
                return response()->json(['message' => 'User has been deleted']);
            }

            return response()->json(['error' => 'Deleting user is failed'], 422);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
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

        if ($user = auth()->user()) {
            return $this->respondWithToken($token, $user);
        }

        return response()->json(['error' => 'Something went wrong, sorry!'], 403);
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh(), $this->user());
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
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
        try {
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);

        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}