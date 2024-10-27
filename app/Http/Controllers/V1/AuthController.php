<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $inputs = $request->validated();
        $user = User::where('email', $inputs['email'])->first();

        if (! $user || ! Hash::check($inputs['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'data' => UserResource::make($user),

            'token' =>   $user->createToken($request->device_name)->plainTextToken,
            'message' => 'login success'
        ]);
    }


    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return response()->json([

            'message' => 'logout success'
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $inputs = $request->validated();
        $user = User::create($inputs);

        return response()->json([
            'data' =>  UserResource::make($user),


            'token' =>   $user->createToken($request->device_name)->plainTextToken,
            'message' => 'registered success'
        ]);
    }

    function me()
    {

        return response()->json([
            'data' => UserResource::make(Auth::user()),

            'message' => 'that is you'
        ]);
    }
}
