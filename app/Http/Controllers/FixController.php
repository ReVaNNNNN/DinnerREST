<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FixController extends Controller
{
    // public function __construct()
    // {
        // $this->middleware('jwt.auth');
    // }

    public function fix()
    {
        return response()->json(['status' => 'success', 'message' => 'Jest git :)'], 200);
    }
}
