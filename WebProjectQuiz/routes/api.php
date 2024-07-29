<?php

use App\Http\Controllers\CsrfTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\UserAuth\AuthController;
use App\Http\Controllers\UserAuth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersTestController;
use App\Http\Controllers\UserAuth\ProfileUpdateController;
use App\Http\Controllers\UserProfileController;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TestResultController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => \App\Http\Middleware\CorsMiddleware::class], function () {


    Route::post('/check-answers', [UsersTestController::class, 'checkAnswers']);
    Route::post('/check-video-answer', [UserProfileController::class, 'checkVideoAnswers']);

    Route::put('/profile/update-password', [ProfileUpdateController::class, 'updatePassword']);
    Route::post('/profile/update-photo/{userId}', [ProfileUpdateController::class, 'updatePhoto']);
    Route::get('/user/{uuid}', [UserProfileController::class, 'getUser']);

    Route::get('/user-test-results/{userId}', [TestResultController::class, 'getUserTestResults']);


    // forgot-password route
    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Kullanıcı bulunamadı.'], 404);
        }

        $token = Str::random(60);

        // Kullanıcıya token'ı atama
        $user->remember_token = $token;
        $user->save();

        // Kullanıcıya mail gönderme
        Mail::to($request->email)->send(new ResetPasswordMail($token, $user->name));

        // JSON response olarak mesaj döndür
        return response()->json(['message' => 'Sıfırlama bağlantısı e-posta adresinize gönderildi.'], 200);
    });

    // reset-password route
    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('remember_token', $request->token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token geçersiz.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->remember_token = null; // Token'ı temizleme
        $user->save();

        return response()->json(['message' => 'Şifre başarıyla sıfırlandı.'], 200);
    });
});
Route::get('auth', [AuthController::class, 'redirectToAuth']);
Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
