<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * @param RegisterFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterFormRequest $request) : string
    {
        try {
            $user = new User();

            $user->setEmail($request->email);
            $user->setPassword($request->password);
            $user->save();

            $this->sendEmailWithVerifyCode($user);
            //tutaj EVENT że nowy user się zarejestrował
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 500);
        }

        return response()->json(['status' =>  'success', 'data' => $user], 200);
    }


    /**
     * @param User $user
     * @throws \Exception
     */
    private function sendEmailWithVerifyCode(User $user)
    {
        $verificationCode = str_random(30);

        $storeCode = $this->storeVerificationCode($user, $verificationCode);

        if (!$storeCode) {
            throw new \Exception("Error while storing verification code for User: " . $user->getEmail());
        }

        $from = env('MAIL_FROM', 'admin@order.pl');
        $subject = 'Order Dinner - Weryfikacja adresu e-mail.';
        $userEmail = $user->getEmail();

        Mail::send('email.registration', ['token' =>$verificationCode],
            function($mail) use ($from, $userEmail, $subject) {
                /** @var Mailable $mail */
                $mail->from($from);
                $mail->to($userEmail);
                $mail->subject($subject);
            });

    }

    /**
     * @param User $user
     * @param string $verificationCode
     *
     * @return bool
     */
    private function storeVerificationCode(User $user, string $verificationCode) : bool
    {
        // zapisywane do bazy created i updated times
        return DB::table('users_verification')->insert(['user_id' => $user->getId(), 'token' => $verificationCode]);
    }

    /**
     * @param string $verificationCode
     *
     * @return string
     */
    public function verifyUser(string $verificationCode) : string
    {
        try {
            $userVerificationCode = $this->getUserVerificationCode($verificationCode);

            if ($userVerificationCode) {
                /** @var User $user */
                $user = User::find($userVerificationCode->user_id);

                if ($user->getEmailVerifiedAt()) {
                    return response()->json(['message' => 'Account already verified..'], 200);
                }

                $user->update(['email_verified_at' => Carbon::now()]); // dodać metodę do usera ?
                DB::table('users_verification')->where('token', $verificationCode)->delete(); // wydzielić do osobnej metody

                //dodać response success
            }
        } catch (ModelNotFoundException $exception) {
                // uzupełnić
        }

        return response(); // dodać response
    }

    /**
     * @param string $verificationCode
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private function getUserVerificationCode(string $verificationCode)
    {
        return DB::table('users_verification')->where('token', $verificationCode)->firstOrFail();
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
