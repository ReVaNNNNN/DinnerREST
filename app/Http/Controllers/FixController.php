<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FixController extends Controller
{
    public function fix()
    {
        /** @var User $user */
        $user = User::find(1);

        // var_dump($user);
        echo $user->getEmail();
        // echo "asdada";
    }
}
