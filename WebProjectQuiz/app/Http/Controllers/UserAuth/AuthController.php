<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    // Google Authentication
    public function redirectToAuth(): JsonResponse
    {
        return response()->json([
            'url' => Socialite::driver('google')
                ->stateless()
                ->redirect()
                ->getTargetUrl(),
        ]);
    }
    // Google Authentication
    // public function handleAuthCallback(): JsonResponse
    // {
    //     try {
    //         /** @var SocialiteUser $socialiteUser */
    //         $socialiteUser = Socialite::driver('google')->stateless()->user();
    //     } catch (ClientException $e) {
    //         $socialiteUser = Socialite::driver('google')->stateless()->user();
    //         return response()->json(['error' => 'Invalid credentials provided.'], 422);
    //     }

    //     /** @var User $user */
    //     $user = User::query()
    //         ->firstOrCreate(
    //             [
    //                 'email' => $socialiteUser->getEmail(),
    //                 'password' => "0",
    //                 'is_admin'=> false, // is_admin değerini false olarak ayarla
    //             ],
    //             [
    //                 'email_verified_at' => now(),
    //                 'name' => $socialiteUser->getName(),
    //                 'google_id' => $socialiteUser->getId(),
    //             ]
    //         );

    //     Auth::login($user);

    //     return response()->json([
    //         'user' => $user,
    //         'access_token' => $user->createToken('google-token')->plainTextToken,
    //         'token_type' => 'Bearer',
    //     ], 200);
    // }
    public function handleAuthCallback(): JsonResponse
    {
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            $socialiteUser = Socialite::driver('google')->stateless()->user();
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        // Kullanıcıyı oluştururken is_admin değerini manuel olarak 0 olarak ayarla
        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => $socialiteUser->getEmail(),
                ],
                [
                    'password' => "0",
                    'email_verified_at' => now(),
                    'name' => $socialiteUser->getName(),
                    'google_id' => $socialiteUser->getId(),
                    'is_admin' => false,
                ]
            );

        Auth::login($user);

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('google-token')->plainTextToken,
            'token_type' => 'Bearer',
        ], 200);
    }

    //KULLANICILAR LİSTESİ
    public function index()
    {
        $users = User::all()->toArray(); // Kullanıcıları diziye dönüştür
        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }
    //Kullanıcı kaydını is_admin değeri 0 olarak kaydediyoruz.
    public function store(Request $request)
    {
        // İstek verilerini doğrulama kurallarına göre kontrol et
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
        ]);
    
        // Doğrulama başarısız olursa, uygun hata mesajlarını ve kodunu dön
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Doğrulama başarılıysa, yeni kullanıcıyı veritabanına kaydet
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->is_admin = 0; // is_admin değeri 0 (false) olarak ayarlanır
        $user->save();
    
        // Başarıyla oluşturulan kullanıcıya ilişkin bilgileri ve başarılı mesajı dön
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
    

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => isset($request->password) ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // public function userLogin(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         // Giriş başarılı olduğunda yapılacak işlemler
    //         return response()->json(['message' => 'Login successful'], 200);
    //     }

    //     // Giriş başarısız olduğunda yapılacak işlemler
    //     return response()->json(['message' => 'Unauthorized'], 401);
    // }

    public function userLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Oturum oluştur
            $request->session()->regenerate();

            // Oturum belirteci oluştur
            $token = Str::random(60); // Rastgele bir oturum belirteci oluştur

            // Oluşturulan oturum belirtecini veritabanına kaydetmek isterseniz:
            // Auth::user()->update(['api_token' => hash('sha256', $token)]);

            // Kullanıcıya oturum belirtecini yanıt olarak gönder
            return response()->json(['token' => $token, 'message' => 'Login successful'], 200);
        }

        // Giriş başarısız olduğunda yapılacak işlemler
        return response()->json(['message' => 'Unauthorized'], 401);
    }


    public function checkUserLoggedIn(Request $request)
    {
        if ($request->user()) {
            return response()->json(['isLoggedIn' => true], 200);
        } else {
            return response()->json(['isLoggedIn' => false], 200);
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function showRegistrationForm()
    {
        return view('userAuth.register');
    }

    // public function register(Request $request)
    // {
    //     // Kullanıcı giriş bilgilerini doğrula
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8|confirmed',
    //     ]);

    //     // Kullanıcıyı kaydet
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Kullanıcıya başarılı kayıt mesajını göster
    //     return redirect('/user/login')->with('success', 'Kaydınız başarıyla tamamlandı! Artık giriş yapabilirsiniz.');
    // }
    public function dash()
    {
        $loginForm1 = Session::get('login_form_1');
        return view('error', compact('loginForm1'));
    }
    public function showLoginForm()
    {
        return view('userAuth.login'); // login.blade.php isimli view dosyasını döndürür

    }
}
