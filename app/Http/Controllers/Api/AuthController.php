<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request){
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->apiResponse(null, 'Invalid credentials', 401, false, 'Invalid credentials');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->apiResponse([
            'user' => new UserResource($user),
            'token' => $token
        ],
            'Login successful'
        );
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return $this->apiResponse(null, 'Successfully logged out');
    }

    public function register(RegistrationRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->apiResponse([
            'user' => new UserResource($user),
            'token' => $token
        ], 'User registered successfully', 201);
    }

    public function me(Request $request)
    {
        return $this->apiResponse(
            new UserResource($request->user()),
            'Authenticated user'
        );
    }
}
