<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FixController extends Controller
{

    public function fix()
    {
        // $order = User::find(24);
        //
        // dd($order->orders()->get());
    }
}
