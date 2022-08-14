<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request){
        $this->validate($request,[
            "name" => 'required|max:255',
            "email" => 'required|email|max:255|unique:users',
            "password" => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (!($token = JWTAuth::attempt($credentials))) {
            return response()->json(['error' => 'invalid credentials'], 400);
        }
        return response()->json([
            'token' => $token
        ]);
    }
}
