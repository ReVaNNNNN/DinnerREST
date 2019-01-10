<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException as ValidException;

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

        return response()->json(['status' => 'success', 'data' => $user], 201);
    }


    /**
     * @param User $user
     * @throws \Exception
     */
    private function sendEmailWithVerifyCode(User $user) : void
    {
        $verificationCode = str_random(30);

        $storeCode = $this->storeVerificationCode($user, $verificationCode);

        if (!$storeCode) {
            throw new \Exception("Error while storing verification code for User: " . $user->getEmail());
       }

        $from = env('MAIL_FROM_ADMIN', 'admin@wybierzobiad.pl');
        $subject = 'Wybierz Obiad - Weryfikacja adresu e-mail.';
        $userEmail = $user->getEmail();

        Mail::send('email.registration', ['token' => $verificationCode],
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
            } else {
                throw new \Exception('Verification code is invalid');
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'You have successfully verified your email address.'], 200);
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
    private function removeVerificationCode(string $verificationCode) : int
    {
        return DB::table('users_verification')->where('token', $verificationCode)->delete();
    }


    /**
     * @param LoginFormRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginFormRequest $request) : string
    {
        $credentials = $request->only('email', 'password');

        try {
            /** @var User $user */
            $user = User::where('email', $request->input('email'))->first();

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(
                    ['status' => 'error', 'message' => 'We cant find an account with this credentials. Please make sure you entered the right information.'],404);
            } elseif ($user && !$user->checkUserIsVerified()) {
                return response()->json(
                    ['status' => 'error', 'message' => 'Your account is not verified. Please check your email and complete verification process.'],404);
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to login, please try again.'], 500);
        }

        return response()->json(['status' => 'success', 'token' => $token], 200);
    }


    /**
     * @param Request $request
     *
     * @return string
     */
    public function logout(Request $request) : string
    {
        try {
            $this->validate($request, ['token' => 'required']);

            JWTAuth::invalidate($request->input('token'));
        } catch (ValidException $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Authorized error. There is no token.'], 401);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to logout, please try again.'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'You have successfully logged out.'], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        try {
            $this->validate($request, ['email' => 'required']);
            $email = $request->only('email');

            if (!User::where('email', $email)->first()) {
                return response()->json(['status' => 'error', 'message' => 'There is no user with given email.'], 401);
            }

            Password::sendResetLink($email, function (Message $message) {
                $message->subject('Twój link do resetowania hasła');
            });
        } catch (ValidException $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => 'There is no address email to reset password.'], 401);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Problem while sending reset link.'], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'A reset email has been sent! Please check your email.'], 200);
    }
}
