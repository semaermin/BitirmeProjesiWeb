<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserProfileController extends Controller
{
    /**
     * Kullanıcı bilgilerini getirir.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($uuid)
    {
        $user = User::where('uuid', $uuid)->first();

        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadı'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

}
