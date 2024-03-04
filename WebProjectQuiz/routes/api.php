<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserAuth\AuthController;

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

// Kullanıcı girişi
Route::post('/login', [AuthController::class, 'login']);

// Kullancı çıkışı
Route::post('/logout', [AuthController::class, 'logout']);
