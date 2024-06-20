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

         // Rank ve seviyeyi güncelle
        $this->updateRanks(); // Kullanıcı sıralamalarını güncelle

        // Sonuçları döndür
        return response()->json([
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
            'unanswered' => $unansweredCount,
            'totalQuestions' => $totalQuestions,
            'totalPoints' => $totalPoints,
            'userPoint' => $user->point, // Bu satırı kaldırın
            'userLevel' => $user->level, // Bu satırı kaldırın
        ]);
    }
    public function checkVideoAnswers(Request $request)
    {
        $userId = $request->input('userId');
        $testId = $request->input('testId');
        $answerId = $request->input('answerId');

        // Test ve soruları al
        $test = Test::with('questions.answers')->find($testId);

        if (!$test) {
            return response()->json(['error' => 'Test bulunamadı.'], 404);
        }

        // Kullanıcıyı bul
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadı.'], 404);
        }

        $userPoint = $user->point; // Kullanıcının mevcut puanını al

        // Verilen cevabın doğruluğunu kontrol et
        $isCorrect = false;
        foreach ($test->questions as $question) {
            $correctAnswer = $question->answers->where('is_correct', true)->first();
            if ($correctAnswer && $correctAnswer->id == $answerId) {
                $isCorrect = true;
                $userPoint += $question->points; // Puanı güncelle
                break;
            }
        }

        // Kullanıcının puanını kaydet
        $user->point = $userPoint;
        $user->save(); // Kullanıcı nesnesinin veritabanına kaydedilmesi

         // Rank ve seviyeyi güncelle
        $this->updateRanks(); // Kullanıcı sıralamalarını güncelle

        // Sonuçları döndür
        return response()->json([
            'is_correct' => $isCorrect,
            'user_point' => $userPoint,
        ]);
    }

    public function updateRanks()
    {
        // Bütün kullanıcıları puanlarına ve seviyelerine göre alın
        $users = User::orderBy('point', 'desc')->get();

        // Her kullanıcı için seviye hesapla ve rank güncelle
        foreach ($users as $index => $user) {
            $points = $user->point;
            $level = 1;
            $points_required_for_next_level = 100; // İlk seviye için gerekli puan

            // Gerekli puanlar geometrik olarak artacak (örneğin, her seviye için %50 daha fazla puan gerekecek)
            // $growth_factor = 1.5;
            $growth_factor = 1.25;

            // Seviye hesaplama: Gerekli puanları aşana kadar level artırma
            while ($points >= $points_required_for_next_level) {
                $points -= $points_required_for_next_level;
                $points_required_for_next_level *= $growth_factor;
                $level++;
            }

            // Rank hesaplama: Sıralı listeye göre rank belirleme
            $rank = $index + 1;

            // Kullanıcının seviye ve rank değerlerini güncelle
            $user->level = $level;
            $user->rank = $rank;
            $user->save(); // Kullanıcı bilgilerini güncelle
        }
    }


}
