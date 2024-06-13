<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;

class UsersTestController extends Controller
{
    // Diğer fonksiyonlar burada olacak (questionStore vb.)

    /**
     * Kullanıcı cevaplarını kontrol eder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAnswers(Request $request)
    {
        $userId = $request->input('userId');
        $testId = $request->input('testId');
        $userAnswers = $request->input('answers'); // Kullanıcı cevapları

        // Testi ve sorularını al
        $test = Test::with('questions.answers')->find($testId);

        if (!$test) {
            return response()->json(['error' => 'Test bulunamadi.'], 404);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadi.'], 404);
        }

        $totalQuestions = $test->questions->count();
        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $totalPoints = 0;

        foreach ($test->questions as $question) {
            // $userAnswers bir dizi mi?
            if (is_array($userAnswers)) {
                // Her bir öğe üzerinde döngü
                foreach ($userAnswers as $userAnswer) {
                    // $userAnswer içinde questionId var mı ve eşleşiyor mu?
                    if (isset($userAnswer['questionId']) && $userAnswer['questionId'] == $question->id) {
                        // Kullanıcının verdiği cevap id'sini al
                        $userAnswerId = $userAnswer['answerId'];
                        // Sorunun doğru cevabını al
                        $correctAnswer = $question->answers->where('is_correct', true)->first();

                        $deneme = $correctAnswer;
                        // Kullanıcının cevabı doğru mu?
                        if ($correctAnswer && $userAnswerId == $correctAnswer->id) {
                            // Doğru cevap verilmişse puanı arttır
                            $correctCount++;
                            $totalPoints += $question->points;
                        } else {
                            // Yanlış cevap verilmişse yanlış sayısını arttır
                            $incorrectCount++;
                        }
                    }
                }
            }
        }



        // Kullanıcının puanını ve seviyesini güncelle
        $user->point += $totalPoints; // Bu satırı kaldırın
        $user->level = $this->calculateLevel($user->point); // Bu satırı kaldırın
        $user->save(); // Bu satırı kaldırın

        // Sonuçları döndür
        return response()->json([
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
            'unanswered' => $unansweredCount,
            'totalQuestions' => $totalQuestions,
            'totalPoints' => $totalPoints,
            'userPoint' => $user->point, // Bu satırı kaldırın
            'userLevel' => $user->level, // Bu satırı kaldırın
            $deneme
        ]);
    }
    public function checkVideoAnswers(Request $request)
    {
        $userId = $request->input('userId');
        $testId = $request->input('testId');
        $userAnswers = $request->input('answers'); // Kullanıcı cevapları

        // Testi ve sorularını al
        $test = Test::with('questions.answers')->find($testId);

        if (!$test) {
            return response()->json(['error' => 'Test bulunamadi.'], 404);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadi.'], 404);
        }

        $totalQuestions = $test->questions->count();
        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $totalPoints = 0;

        foreach ($test->questions as $question) {
            // Kullanıcının bu soru için cevabı var mı?
            $userAnswer = collect($userAnswers)->firstWhere('questionId', $question->id);

            if ($userAnswer) {
                // Kullanıcının verdiği cevap id'sini al
                $userAnswerId = $userAnswer['answerId'];
                // Sorunun doğru cevabını al
                $correctAnswer = $question->answers->where('is_correct', true)->first();

                // Kullanıcının cevabı doğru mu?
                if ($correctAnswer && $userAnswerId == $correctAnswer->id) {
                    // Doğru cevap verilmişse puanı arttır
                    $correctCount++;
                    $totalPoints += $question->points;
                } else {
                    // Yanlış cevap verilmişse yanlış sayısını arttır
                    $incorrectCount++;
                }
            } else {
                // Kullanıcı cevap vermemişse
                $unansweredCount++;
            }
        }

        // Sonuçları döndür
        return response()->json([
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
            'unanswered' => $unansweredCount,
            'totalQuestions' => $totalQuestions,
            'totalPoints' => $totalPoints,
        ]);
    }


    /**
     * Kullanıcının seviyesini hesapla.
     *
     * @param int $point
     * @return int
     */
    private function calculateLevel($point)
    {
        // Basit bir seviye hesaplama algoritması, ihtiyacınıza göre özelleştirin
        return floor($point / 100) + 1;
    }
}
