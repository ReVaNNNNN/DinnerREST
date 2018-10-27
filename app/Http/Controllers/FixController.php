<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FixController extends Controller
{
    public function fix()
    {

        // $from = env("MAIL_USERNAME");
        $from = "revan5480@wp.pl";
        // dd($from);
        $userEmail = "revannn5480@gmail.com";
        $verificationCode = 123456789;

        Mail::send('email.registration', ['token' => $verificationCode],
            function($mail) use ($userEmail, $from) {
                /** @var Mailable $mail */
                $mail->from('Administracja OrderDinner'); // <--- do configa
                $mail->to($userEmail);
                $mail->subject("Testowy E-mail Kamiś");
            });
    }
}
