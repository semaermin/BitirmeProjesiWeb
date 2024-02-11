<?php

use Illuminate\Support\Facades\Route;
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
    Route::post('/quiz/create', [QuizController::class, 'questionShow'])->name('quiz.store'); // Test ekleme formundan gelen verileri işleme
    Route::get('/quiz/{slug}', [QuizController::class, 'quizShow'])->name('quiz.show');

    Route::get('/quiz/{slug}/edit', [QuizController::class, 'edit'])->name('quiz.edit');
    Route::put('/quiz/{slug}', [QuizController::class, 'update'])->name('quiz.update');


});


// Kayıt formunu göstermek için
Route::get('/user/register', [AuthController::class, 'showRegistrationForm'])->name('user.register');
// Kayıt formunun gönderildiği route
Route::post('/user/register', [AuthController::class, 'store']);

//Giris formunu göstermek için
Route::get ('/user/login', [AuthController::class, 'showLoginForm'])->name('user.login');
Route::post('/user/login', [AuthController::class, 'login'])->name('user.login');

Route::get('/error', [AuthController::class, 'dash'])->name('error');
