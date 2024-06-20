<?php

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileUpdateController extends Controller
{
    public function updatePassword(Request $request)
    {
        // Kullanıcıyı bul
        $user = User::find($request->input('user_id'));

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Form validasyonu
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        // Mevcut şifrenin doğru olup olmadığını kontrol et
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'The current password is incorrect'], 400);
        }

        // Yeni şifreyi güncelle
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => 'Password updated successfully'], 200);
    }


    public function updatePhoto(Request $request, $userId)
    {
        // Kullanıcıyı bul
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadı'], 404);
        }

        // Gelen isteği kontrol et
        if ($request->hasFile('profile_photo')) {
            try {
                // Önceki profil fotoğrafını silme (isteğe bağlı)
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                // Yeni fotoğrafı yükleme
                $imageInput = $request->file('profile_photo');
                $fileName = time() . '_' . $imageInput->getClientOriginalName();
                $path = $imageInput->storeAs('profile_photo', $fileName, 'public');
                $user->profile_photo_path = $path;
                $user->save();

                return response()->json(['success' => 'Profil fotoğrafı başarıyla güncellendi'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Dosya yüklenemedi: ' . $e->getMessage()], 400);
            }
        }

        return response()->json(['error' => 'Fotoğraf yüklenemedi'], 400);
    }




}
