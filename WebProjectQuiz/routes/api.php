<?php

use App\Http\Controllers\CsrfTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\UserAuth\AuthController;
use Illuminate\Support\Facades\Auth;

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

Route::group(['middleware' => 'cors'], function () {
    Route::get('auth', [AuthController::class, 'redirectToAuth']);
    Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
    // Route::post('/user/login', [AuthController::class, 'login']);
    Route::get('/user/register', [AuthController::class, 'showRegistrationForm'])->name('user.register');
    Route::post('/user/register', [AuthController::class, 'store']);
});

Route::get('/csrf-token', [CsrfTokenController::class, 'getToken']);


Route::get('/userLoggedIn', function () {
    return response()->json(['isLoggedIn' => Auth::check()]);
});
