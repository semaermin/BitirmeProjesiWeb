<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\UserAuth\AuthController;
use App\Http\Controllers\UserAuth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersTestController;
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

    //testleri listele
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.quiz');
    //Test oluşturma formu
    Route::get('/quiz/create', [QuizController::class, 'create'])->name('quiz.add'); // Test ekleme formunu gösterme

    //Olusturulan testten gelen verileri alip kaydetme islemini yapar.
    Route::post('/quiz/create', [QuizController::class, 'questionStore'])->name('quiz.store'); // Test ekleme formundan gelen verileri işleme
    Route::get('/quiz/{slug}', [QuizController::class, 'quizShow'])->name('quiz.show');

    Route::get('/quiz/{slug}/edit', [QuizController::class, 'edit'])->name('quiz.edit'); //daha önceki testi gösterir
    Route::put('/quiz/{slug}', [QuizController::class, 'update'])->name('quiz.update'); // update fonksiyonu
});


// Tüm kullanıcıları listele
Route::get('/users', [AuthController::class, 'index']);

// // Belirli bir kullanıcıyı göster
// Route::get('/users/{id}', [AuthController::class, 'show']);

// // Yeni bir kullanıcı oluştur
// Route::post('/users', [AuthController::class, 'store']);

// // Bir kullanıcıyı güncelle
// Route::put('/users/{id}', [AuthController::class, 'update']);

// // Bir kullanıcıyı sil
// Route::delete('/users/{id}', [AuthController::class, 'destroy']);


// CORS HATASI ALMAMAK İÇİN MİDDLEWARE YAZDIK KULLANIYORUZ.
Route::group(['middleware' => \App\Http\Middleware\CorsMiddleware::class], function () {
    Route::get('auth', [AuthController::class, 'redirectToAuth']);
    Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);
    Route::get('user/logout', [AuthController::class, 'logout']);
    // Route::post('/user/login', [AuthController::class, 'login']);
    // Route::get('/user/register', [AuthController::class, 'showRegistrationForm'])->name('user.register');
    Route::post('/user/register', [AuthController::class, 'store']);


});
// Route::post('/check-answers', [UsersTestController::class, 'checkAnswers']);
// // Kayıt formunu göstermek için
// Route::get('/user/register', [AuthController::class, 'showRegistrationForm'])->name('user.register');
// Kayıt formunun gönderildiği route
Route::post('/user/register', [AuthController::class, 'store']);

// Giriş formunu göstermek için
// Route::get('/user/login', [AuthController::class, 'showLoginForm'])->name('user.login');
Route::get('/error', [AuthController::class, 'dash'])->name('error');

Route::group(['middleware' => ['web']], function () {
    // Oturum yönetimi gerektiren rotalar buraya gelecek
    Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login');
});

Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

Route::post('user/reset-password', [ResetPasswordController::class, 'reset']);
Route::post('user/reset-password-process', [ResetPasswordController::class, 'resetPassword']);

//Testleri listeliyorum
Route::get('/test-list', [HomeController::class, 'testList']);
//Testlerin verileri
Route::get('/test-list/{slug}', [HomeController::class, 'testData']);

//videolu soruları gönder
Route::get('/video-list', [VideoController::class, 'videoList']);
// Route::get('/video', [VideoController::class, 'getVideoQuestions']);


    // Şifremi Unuttum İsteği
    Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);

    // Şifre Sıfırlama İsteği
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
