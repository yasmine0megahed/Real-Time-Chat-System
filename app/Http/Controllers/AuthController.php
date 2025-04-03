<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'user created successfully',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            // Attempt to authenticate the user and generate JWT token
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
            $user = User::where('email', '=', $request->email)->firstOrFail();
            return response()->json(
                [
                    'message' => 'success',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => new UserResource($user)
                ],
                200
            );
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken()); // Invalidate JWT Token
            return response()->json(['message' => 'logged out successfully'], 200);
        } catch (JWTException $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}
