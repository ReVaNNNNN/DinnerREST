<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function registeredUsers() : JsonResponse
    {
        $users = User::where('role_id', Role::USER)->where('test', 0)->get();

        return response()->json(['status' => 'success', 'users' => $users], 200);
    }
}
