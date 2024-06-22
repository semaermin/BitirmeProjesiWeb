<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\UserAuth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VideoController;

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

    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.quiz');
    Route::get('/quiz/create', [QuizController::class, 'create'])->name('quiz.add');

    Route::post('/quiz/create', [QuizController::class, 'questionStore'])->name('quiz.store');
    Route::get('/quiz/{slug}', [QuizController::class, 'quizShow'])->name('quiz.show');

    Route::get('/quiz/{slug}/edit', [QuizController::class, 'edit'])->name('quiz.edit');
    Route::put('/quiz/{slug}', [QuizController::class, 'update'])->name('quiz.update');
});

Route::get('/users', [AuthController::class, 'index']);

// CORS
Route::group(['middleware' => \App\Http\Middleware\CorsMiddleware::class], function () {
    Route::get('auth', [AuthController::class, 'redirectToAuth']);
    Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
    Route::post('/user/register', [AuthController::class, 'store']);
});

Route::group(['middleware' => ['web']], function () {
    // Oturum yönetimi
    Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login');
});

Route::get('/test-list', [HomeController::class, 'testList']);
Route::get('/test-list/{slug}', [HomeController::class, 'testData']);
Route::get('/video-list', [VideoController::class, 'videoList']);
