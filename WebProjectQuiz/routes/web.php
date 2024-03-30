<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\UserAuth\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [Controller::class, 'index'])->name('dashboard');

    Route::get('/quiz',[QuizController::class,'index'])->name('quiz.quiz');
    //Test oluşturma formu
    Route::get('/quiz/create', [QuizController::class, 'create'])->name('quiz.add'); // Test ekleme formunu gösterme

    //Olusturulan testten gelen verileri alip kaydetme islemini yapar.
    Route::post('/quiz/create', [QuizController::class, 'questionStore'])->name('quiz.store'); // Test ekleme formundan gelen verileri işleme
    Route::get('/quiz/{slug}', [QuizController::class, 'quizShow'])->name('quiz.show');

    Route::get('/quiz/{slug}/edit', [QuizController::class, 'edit'])->name('quiz.edit');
    Route::put('/quiz/{slug}', [QuizController::class, 'update'])->name('quiz.update');


});


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

Route::get('auth', [AuthController::class, 'redirectToAuth']);
Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
