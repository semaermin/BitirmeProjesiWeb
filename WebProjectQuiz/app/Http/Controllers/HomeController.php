<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\Test;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    public function testList()
    {

        // testleri listele son 10 tanesi
        $tests = Test::latest()->take(10)->get();
        return response()->json(['tests' => $tests]);

    }
    public function testData($slug)
    {
        try {
            // Slug'a göre ilgili testi bul ve ilişkili soruları ve cevaplarıyla birlikte getir
            $test = Test::where('slug', $slug)
                        ->with('questions', 'questions.answers', 'questions.matchingOptions')
                        ->firstOrFail();

            // Mevcut testin adını al
            $testName = $test->name;

            return response()->json(['testName' => $testName, 'test' => $test], 200);
        } catch (\Exception $e) {
            // Hata durumunda uygun bir hata yanıtı döndür
            return response()->json(['message' => 'Bir hata oluştu.'], 500);
        }
    }

}
