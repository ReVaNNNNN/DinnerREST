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
        return DB::table('users_verification')->insert([
            'user_id' => $user->getId(),
            'token' => $verificationCode,
            'updated_at' => Carbon::now(),
            'created_at' => Carbon::now()
        ]);
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

                $user->setEmailVerifiedAt(Carbon::now());
                $user->save();
                $this->removeVerificationCode($verificationCode);

                return response()->json(['status' => 'success', 'message' => 'You have successfully verified your email address.'], 200);
            } else {
                throw new \Exception('Verification code is invalid');
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 500);
        }

    }

    /**
     * @param string $verificationCode
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private function getUserVerificationCode(string $verificationCode)
    {
        return DB::table('users_verification')->where('token', $verificationCode)->first();
    }

    /**
     * @param string $verificationCode
     *
     * @return int
     */
    private function removeVerificationCode(string $verificationCode)
    {
        return DB::table('users_verification')->where('token', $verificationCode)->delete();
    }


    public function login(Request $request)
    {
        // nowy rodzaj Requesta LoginRequest ?
        $credentials = $request->only('email', 'password');



        // return response()->json(['status' => 'success', 'token' => $token], 200);
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
