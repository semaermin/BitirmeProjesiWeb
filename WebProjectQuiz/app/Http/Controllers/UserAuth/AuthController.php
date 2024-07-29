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
    public function redirectToAuth(): JsonResponse
    {
        try {
            $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
            return response()->json(['url' => $url, 'message' => 'Redirecting to Google Auth'], 200);
        } catch (\Exception $e) {
            \Log::error('Redirect to Google Auth Error: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to initiate Google Auth: ' . $e->getMessage()], 500);
        }
    }

    public function handleAuthCallback(): JsonResponse
    {
        try {
            $socialiteUser = Socialite::driver('google')->stateless()->user();
        } catch (ClientException $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid credentials provided.'], 422);
        } catch (\Exception $e) {
            \Log::error('General Error: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred. Please try again later.'], 500);
        }

        $user = User::updateOrCreate(
            ['email' => $socialiteUser->getEmail()],
            [
                'name' => $socialiteUser->getName(),
                'google_id' => $socialiteUser->getId(),
                'email_verified_at' => now(),
                'is_admin' => false,
                'password' => Hash::make(Str::random(24)),
            ]
        );

        Auth::login($user);

        $accessToken = $user->createToken('google-token')->plainTextToken;
        $refreshToken = $this->generateRefreshToken($user);

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer',
        ])->cookie('access_token', $accessToken, config('session.lifetime'), null, null, true, true)
          ->cookie('refresh_token', $refreshToken, config('session.lifetime'), null, null, true, true);
    }

    protected function generateRefreshToken(User $user)
    {
        $refreshToken = Str::random(60);
        $user->refreshTokens()->create(['token' => hash('sha256', $refreshToken)]);
        return $refreshToken;
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

    // public function store(Request $request)
    // {
    //     // İstek verilerini doğrulama kurallarına göre kontrol et
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|unique:users|max:255',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     // Doğrulama başarısız olursa, uygun hata mesajlarını ve kodunu dön
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     // Doğrulama başarılıysa, yeni kullanıcıyı veritabanına kaydet
    //     $user = new User([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     $user->is_admin = 0; // is_admin değeri 0 (false) olarak ayarlanır
    //     $user->point = 0;
    //     $user->level = 1;
    //     $user->profile_photo_path = "";
    //     $user->save();

    //     // Kullanıcıların puanlarına göre sıralamasını yap
    //     $rankedUsers = User::orderBy('level', 'desc')
    //         ->orderBy('point', 'desc')
    //         ->get();
    //     // Yeni kullanıcının sıralamasını bul
    //     $rank = $rankedUsers->search(function ($item) use ($user) {
    //         return $item->id === $user->id;
    //     });

    //     // Sıralama bulunamazsa, kullanıcının sıralamasını 0 olarak ayarla
    //     if ($rank === false) {
    //         $rank = 0;
    //     } else {
    //         // Sıralama bulunursa, 1 ekleyerek insanların 1'den başlayan sıralamasını sağla
    //         $rank += 1;
    //     }

    //     // Kullanıcının sıralamasını güncelle
    //     $user->rank = $rank;
    //     $user->save();

    //     // JWT token oluştur
    //     $token = $user->createToken('MyApp')->plainTextToken;

    //     // Başarıyla oluşturulan kullanıcıya ilişkin bilgileri ve başarılı mesajı token ile birlikte dön
    //     return response()->json([
    //         'token' => $token,
    //         'user' => $user,
    //         'message' => 'User created successfully',
    //     ], 201);
    // }

    /**
     * Store a newly created user in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => 0,
                'point' => 0,
                'level' => 1,
                'profile_photo_path' => '',
            ]);

            $rankedUsers = User::orderBy('level', 'desc')
                ->orderBy('point', 'desc')
                ->get();

            $rank = $rankedUsers->search(function ($item) use ($user) {
                return $item->id === $user->id;
            });

            $rank = $rank === false ? 0 : $rank + 1;
            $user->rank = $rank;
            $user->save();

            $accessToken = $user->createToken('access_token')->plainTextToken;
            $refreshToken = $user->createToken('refresh_token', ['role:refresh'])->plainTextToken;

            return response()->json([
                'user' => $user,
                'message' => 'User created successfully',
            ], 201)
            ->cookie('access_token', $accessToken, 60, null, null, true, true)
            ->cookie('refresh_token', $refreshToken, 1440, null, null, true, true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User creation failed',
                'message' => $e->getMessage(),
            ], 500);
        }
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

    // public function userLogin(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         // Kullanıcı doğrulandı, JWT token oluştur
    //         $user = Auth::user();
    //         // dd($user);
    //         $token = $user->createToken('MyApp')->plainTextToken;

    //         // Kullanıcıların puanlarına göre sıralamasını yap
    //         $rankedUsers = User::orderBy('level', 'desc')
    //             ->orderBy('point', 'desc')
    //             ->get();            // Yeni kullanıcının sıralamasını bul
    //         $rank = $rankedUsers->search(function ($item) use ($user) {
    //             return $item->id === $user->id;
    //         });

    //         // Sıralama bulunamazsa, kullanıcının sıralamasını 0 olarak ayarla
    //         if ($rank === false) {
    //             $rank = 0;
    //         } else {
    //             // Sıralama bulunursa, 1 ekleyerek insanların 1'den başlayan sıralamasını sağla
    //             $rank += 1;
    //         }

    //         // Kullanıcının sıralamasını güncelle
    //         $user->rank = $rank;

    //         // Token'i kullanıcıya yanıt olarak gönder
    //         return response()->json(['token' => $token, 'user' => $user, 'message' => 'Login successful'], 200);
    //     }

    //     // Giriş başarısız olduğunda yapılacak işlemler
    //     return response()->json(['message' => 'Unauthorized'], 401);
    // }

    /**
     * Login the user and generate a JWT token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $user = Auth::user();

            $accessToken = $user->createToken('access_token')->plainTextToken;
            $refreshToken = $user->createToken('refresh_token', ['role:refresh'])->plainTextToken;

            $rankedUsers = User::orderBy('level', 'desc')
                ->orderBy('point', 'desc')
                ->get();

            $rank = $rankedUsers->search(function ($item) use ($user) {
                return $item->id === $user->id;
            });

            $rank = $rank === false ? 0 : $rank + 1;
            $user->rank = $rank;
            $user->save();

            return response()->json([
                'user' => $user,
                'message' => 'Login successful',
            ], 200)
            ->cookie('access_token', $accessToken, 60, null, null, true, true)
            ->cookie('refresh_token', $refreshToken, 1440, null, null, true, true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Login failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    public function showLoginForm()
    {
        return view('userAuth.login'); // login.blade.php isimli view dosyasını döndürür

    }

    public function refreshToken(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');
        if (!$refreshToken) {
            return response()->json(['message' => 'Refresh token not found'], 401);
        }

        try {
            $user = Auth::setToken($refreshToken)->user();

            $accessToken = $user->createToken('access_token')->plainTextToken;

            return response()->json(['access_token' => $accessToken], 200)
                ->cookie('access_token', $accessToken, 60, null, null, true, true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token refresh failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Mevcut kullanıcının tüm token'larını iptal et
            $user = $request->user();
            $user->tokens()->delete();

            // Token'ları cookie'den temizle
            return response()->json([
                'message' => 'Logged out successfully'
            ], 200)
            ->cookie('access_token', '', -1, null, null, true, true)
            ->cookie('refresh_token', '', -1, null, null, true, true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Logout failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }



}
