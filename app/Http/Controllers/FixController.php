<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FixController extends Controller
{
    public function fix()
    {
        $user = User::find(Auth::id());

        var_dump($user);
    }
}
