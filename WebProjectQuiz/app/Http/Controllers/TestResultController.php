<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestResult;
use App\Models\User;

class TestResultController extends Controller
{
    public function getUserTestResults($userId)
    {
        // Kullanıcıyı bulun
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadi.'], 404);
        }

        // Kullanıcının test sonuçlarını alın
        $testResults = TestResult::where('user_id', $userId)->get();

        // Test sonuçları yoksa boş bir cevap döndür
        if ($testResults->isEmpty()) {
            return response()->json(['message' => 'Kullanıcının test sonuçları bulunamadı.'], 200);
        }

        // Toplam doğru ve yanlış yüzdelerini hesaplayın
        $totalCorrectPercentage = $testResults->avg('correct_percentage');
        $totalIncorrectPercentage = 100 - $totalCorrectPercentage;

        // Sonuçları döndür
        return response()->json([
            'userId' => $userId,
            'totalCorrectPercentage' => $totalCorrectPercentage,
            'totalIncorrectPercentage' => $totalIncorrectPercentage,
        ]);
    }
}
