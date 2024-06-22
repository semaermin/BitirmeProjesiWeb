<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    public function videoList()
    {
        try {
            $videoQuestion = Question::where('is_video', 1)
                                    ->with('answers')
                                    ->inRandomOrder()
                                    ->first();

            if (!$videoQuestion) {
                return response()->json(['message' => 'Videolu soru bulunamadı.'], 404);
            }

            return response()->json(['videoQuestion' => $videoQuestion], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Bir hata oluştu.'], 500);
        }
    }

}
