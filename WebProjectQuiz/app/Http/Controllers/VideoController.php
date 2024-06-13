<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    // public function videoList()
    // {
    //     try {
    //         // Soruları listele
    //         $questions = Question::where('is_video', 1)
    //             ->with('matchingOptions')
    //             // ->where('difficulty')
    //             ->get();

    //         return response()->json(['questions' => $questions], 200);
    //     } catch (\Exception $e) {
    //         // Hata durumunda uygun bir hata yanıtı döndür
    //         return response()->json(['message' => 'Bir hata oluştu.'], 500);
    //     }
    // }
    public function videoList()
    {
        try {
            // Videolu soruları listele ve ilişkili cevaplarıyla birlikte getir
            $videoQuestions = Question::where('is_video', 1)
                                    ->with('answers')
                                    ->inRandomOrder()
                                    ->get();

            return response()->json(['videoQuestions' => $videoQuestions], 200);
        } catch (\Exception $e) {
            // Hata durumunda uygun bir hata yanıtı döndür
            return response()->json(['message' => 'Bir hata oluştu.'], 500);
        }
    }

    // public function getVideoQuestions(Request $request)
    // {
    //     // Kullanıcının oturum bilgilerini kullanarak kullanıcıyı al
    //     $user = $request->user();
    //     // dd($user);
    //     // Kullanıcının seviyesine göre soruları almak için örnek bir sorgu
    //     $videoQuestions = Question::where('is_video', 1)
    //         ->where('difficulty', $user->level) // Kullanıcının seviyesine göre
    //         ->get();
    //     dd($videoQuestions);
    //     // Eğer daha fazla filtreleme veya sıralama yapmak isterseniz buraya ekleyebilirsiniz

    //     return response()->json(['questions' => $videoQuestions], 200);
    // }



}
