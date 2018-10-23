<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User();

        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['status' =>  'success', 'data' => $user], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Błędne dane logowania'
            ], 400);
        }

        return response()->json(['status' => 'success', 'token' => $token], 200);
    }

    public function logout()
    {
        JWTAuth::invalidate();

        return response()->json(['status' => 'success', 'msg' => 'Pomyślnie wylogowano'], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::id());

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }
}
