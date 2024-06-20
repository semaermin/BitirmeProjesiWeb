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
// Route::post('/register', [Controller::class, 'register']);
// Route::post('/login', [Controller::class, 'login']);
// Route::get('/profile', [Controller::class, 'profile'])->middleware('auth:sanctum');


// Tüm kullanıcıları listele
Route::get('/users', [AuthController::class, 'index']);

// Belirli bir kullanıcıyı göster
Route::get('/users/{id}', [AuthController::class, 'show']);

// Yeni bir kullanıcı oluştur
Route::post('/users', [AuthController::class, 'store']);

// Bir kullanıcıyı güncelle
Route::put('/users/{id}', [AuthController::class, 'update']);

// Bir kullanıcıyı sil
Route::delete('/users/{id}', [AuthController::class, 'destroy']);

// // Kullanıcı girişi
// Route::post('user/login', [AuthController::class, 'login']);

// Kullanıcı girişi - Google ile giriş
Route::post('/login/google', [AuthController::class, 'loginWithGoogle']);

// Kullanıcı çıkışı
Route::post('/logout', [AuthController::class, 'logout']);


// Route::group(['middleware' => 'cors'], function () {
//     Route::get('auth', [AuthController::class, 'redirectToAuth']);
//     Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
// });

Route::group(['middleware' => \App\Http\Middleware\CorsMiddleware::class], function () {
    Route::get('auth', [AuthController::class, 'redirectToAuth']);
    Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
    // Route::post('/user/login', [AuthController::class, 'login']);
    Route::get('/user/register', [AuthController::class, 'showRegistrationForm'])->name('user.register');
    Route::post('/user/register', [AuthController::class, 'store']);
    Route::post('/check-answers', [UsersTestController::class, 'checkAnswers']);
    Route::post('/check-video-answers', [UsersTestController::class, 'checkVideoAnswers']);
    Route::put('/profile/update-password', [ProfileUpdateController::class, 'updatePassword']);
    Route::post('/profile/update-photo/{userId}', [ProfileUpdateController::class, 'updatePhoto']);
    Route::get('/user/{id}', [UserProfileController::class, 'getUser']);

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

        return response()->json(['message' => 'Sıfırlama bağlantısı e-posta adresinize gönderildi.'], 200);
    });

    // reset-password route
    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed',
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

Route::get('/csrf-token', [CsrfTokenController::class, 'getToken']);


Route::get('/userLoggedIn', function () {
    return response()->json(['isLoggedIn' => Auth::check()]);
});
