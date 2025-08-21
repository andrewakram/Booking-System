<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\V1\Auth\LoginUserRequest;
use App\Http\Resources\V1\Auth\LoginUserResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponserTrait;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use ApiResponserTrait;

    public function login(LoginUserRequest $request)
    {

        if (!Auth::attempt($request->validated())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        // Revoke previous tokens if needed
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;
        $user->access_token = $token;

        return $this->successResponse(new LoginUserResource($user));
    }
}

