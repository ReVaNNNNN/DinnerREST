<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @param RegisterFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterFormRequest $request)
    {
        $user = new User();

        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $this->sendEmailWithVerifyCode($user); // <---- try catch ? zrobić obłusgę błędów

        return response()->json(['status' =>  'success', 'data' => $user], 200);
    }

    /**
     * @param User $user
     */
    private function sendEmailWithVerifyCode(User $user)
    {
        $verificationCode = str_random(30);

        $this->storeVerificationCodeInDB($user, $verificationCode);

        $subject = 'Order Dinner - Weryfikacja adresu e-mail.';
        $userEmail = $user->getEmail();

        Mail::send('email.verify', ['token' =>$verificationCode],
            function($mail) use ($userEmail, $subject) {
                $mail->from('Administracja OrderDinner'); // <--- do configa
                $mail->to($userEmail);
                $mail->subject($subject);
            });

    }

    /**
     * @param User $user
     * @param string $verificationCode
     * @return bool
     */
    private function storeVerificationCodeInDB(User $user, string $verificationCode)
    {
        return DB::table('users_verification')->insert(['user_id' => $user->getId(), 'token' => $verificationCode]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        JWTAuth::invalidate();

        return response()->json(['status' => 'success', 'msg' => 'Pomyślnie wylogowano'], 200);
    }

    // public function user(Request $request)
    // {
    //     $user = User::find(Auth::id());
    //
    //     return response()->json(['status' => 'success', 'data' => $user], 200);
    // }
}
