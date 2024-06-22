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
use Carbon\Carbon;
use Throwable;


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
                'message' => 'Başarılı bir şekilde oturum açıldı.',
        ]);
    }
    // Google Authentication
    public function handleAuthCallback(): JsonResponse
    {
        try {
            /** @var SocialiteUser $socialiteUser */
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            $socialiteUser = Socialite::driver('google')->stateless()->user();
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        }

        /** @var User $user */
        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => $socialiteUser->getEmail(),
                    'password' => "0",
                    'is_admin'=> false, // is_admin değerini false olarak ayarla
                ],
                [
                    'email_verified_at' => now(),
                    'name' => $socialiteUser->getName(),
                    'google_id' => $socialiteUser->getId(),
                ]
            );

        Auth::login($user);

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('google-token')->plainTextToken,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function index()
    {
        $users = User::where('is_admin', 0)
            ->orderBy('level', 'desc')
            ->orderBy('point', 'desc')
            ->take(50)
            ->get(['id', 'name', 'level', 'point'])
            ->toArray();

        return response()->json(['users' => $users], 200);
    }


    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
    }

    public function store(Request $request)
    {
        // İstek verilerini doğrulama kurallarına göre kontrol et
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
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
        $user->point = 0;
        $user->level = 1;
        $user->profile_photo_path = "";
        $user->save();

        // Kullanıcıların puanlarına göre sıralamasını yap
        $rankedUsers = User::orderBy('level', 'desc')
            ->orderBy('point', 'desc')
            ->get();
        // Yeni kullanıcının sıralamasını bul
        $rank = $rankedUsers->search(function ($item) use ($user) {
            return $item->id === $user->id;
        });

        // Sıralama bulunamazsa, kullanıcının sıralamasını 0 olarak ayarla
        if ($rank === false) {
            $rank = 0;
        } else {
            // Sıralama bulunursa, 1 ekleyerek insanların 1'den başlayan sıralamasını sağla
            $rank += 1;
        }

        // Kullanıcının sıralamasını güncelle
        $user->rank = $rank;
        $user->save();

        // Başarıyla oluşturulan kullanıcıya ilişkin bilgileri ve başarılı mesajı dön
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8',
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

    public function userLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Kullanıcı doğrulandı, JWT token oluştur
            $user = Auth::user();
            // dd($user);
            $token = $user->createToken('MyApp')->plainTextToken;

            // Kullanıcıların puanlarına göre sıralamasını yap
            $rankedUsers = User::orderBy('level', 'desc')
                ->orderBy('point', 'desc')
                ->get();            // Yeni kullanıcının sıralamasını bul
            $rank = $rankedUsers->search(function ($item) use ($user) {
                return $item->id === $user->id;
            });

            // Sıralama bulunamazsa, kullanıcının sıralamasını 0 olarak ayarla
            if ($rank === false) {
                $rank = 0;
            } else {
                // Sıralama bulunursa, 1 ekleyerek insanların 1'den başlayan sıralamasını sağla
                $rank += 1;
            }

            // Kullanıcının sıralamasını güncelle
            $user->rank = $rank;

            // Token'i kullanıcıya yanıt olarak gönder
            return response()->json(['token' => $token, 'user' => $user, 'message' => 'Login successful'], 200);
        }

        // Giriş başarısız olduğunda yapılacak işlemler
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function showLoginForm()
    {
        return view('userAuth.login'); // login.blade.php isimli view dosyasını döndürür

    }

}
