<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User();

        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        // zastanowić się jakie dane potrzebuję do rejestracji usera
        // dodać migrację usera z domyślnymi polami jak rola itp
        // postmanem sprawdzić rejestrację i logowanie usera
        return response()->json(['status' =>  'success', 'data' => $user], 200);
    }
}