<?php

namespace App\Http\Controllers\UserAuth;

use App\Actions\Fortify\ResetUserPassword;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /**
     * Şifre sıfırlama bağlantısı gönderme.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function reset(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $status = Password::sendResetLink($request->only('email'));

    //     return $status === Password::RESET_LINK_SENT
    //                 ? response()->json(['message' => __($status)])
    //                 : response()->json(['message' => __($status)], 400);
    // }

    // /**
    //  * Şifre sıfırlama işlemini gerçekleştirme.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function resetPassword(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'token' => 'required|string',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
    //         $user->forceFill([
    //             'password' => Hash::make($password)
    //         ])->saveQuietly();

    //         $user->setRememberToken(Str::random(60));

    //         event(new PasswordReset($user));
    //     });

    //     return $status === Password::PASSWORD_RESET
    //                 ? response()->json(['message' => __($status)])
    //                 : response()->json(['message' => __($status)], 400);
    // }

    // Şifremi Unuttum İsteği
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Şifre sıfırlama bağlantısı e-posta adresinize gönderildi'], 200)
            : response()->json(['error' => 'Bir hata oluştu. Lütfen tekrar deneyin.'], 400);
    }

    // Şifre Sıfırlama İsteği
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status == Password::PASSWORD_RESET
                    ? response()->json(['message' => __($status)], 200)
                    : response()->json(['error' => __($status)], 400);
    }
}
