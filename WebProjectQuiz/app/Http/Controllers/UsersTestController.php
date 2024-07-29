<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\TestResult;

class UsersTestController extends Controller
{
    /**
     * Kullanıcı cevaplarını kontrol eder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAnswers(Request $request)
    {
        $userUUID = $request->input('userUUID');
        $testId = $request->input('testId');
        $userAnswers = $request->input('answers');

        $test = Test::with('questions.answers')->find($testId);

        if (!$test) {
            return response()->json(['error' => 'Test bulunamadi.'], 404);
        }

        $user = User::where('uuid', $userUUID)->first();
        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadi.'], 404);
        }

        $totalQuestions = $test->questions->count();
        $correctCount = 0;
        $incorrectCount = 0;
        $unansweredCount = 0;
        $totalPoints = 0;

        foreach ($test->questions as $question) {
                foreach ($userAnswers as $userAnswer) {
                    if (isset($userAnswer['questionId']) && $userAnswer['questionId'] == $question->id) {
                        $userAnswerId = $userAnswer['answerId'];
                        $correctAnswer = $question->answers->where('is_correct', true)->first();

                        if ($correctAnswer && $userAnswerId == $correctAnswer->id) {
                            $correctCount++;
                            $totalPoints += $question->points;
                        } else {
                            $incorrectCount++;
                        }

                        // Cevapları user_answers tablosuna kaydet
                        \DB::table('user_answers')->insert([
                            'user_uuid' => $userUUID,
                            'question_id' => $question->id,
                            'answer_id' => $userAnswerId,
                            'is_correct' => $correctAnswer && $userAnswerId == $correctAnswer->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
        }

        $correctPercentage = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

        TestResult::create([
            'user_id' => $user->id,
            'test_id' => $testId,
            'correct_percentage' => $correctPercentage,
        ]);

        $user->point += $totalPoints;
        $user->save();

        $this->updateRanks();

        $user->refresh();

        return response()->json([
            'correctCount' => $correctCount,
            'incorrectCount' => $incorrectCount,
            'unanswered' => $unansweredCount,
            'totalQuestions' => $totalQuestions,
            'totalPoints' => $totalPoints,
            'correctPercentage' => $correctPercentage,
            'userLevel' => $user->level,
            'userPoint' => $user->point,
        ]);
    }


    public function checkVideoAnswers(Request $request)
    {
        $userUUID = $request->input('userUUID');
        $questionId = $request->input('questionId');
        $answerId = $request->input('answerId');

        // Kullanıcıyı bul
        $user = User::where('uuid', $userUUID)->first();
        if (!$user) {
            return response()->json(['error' => 'Kullanıcı bulunamadi.'], 404);
        }

        // Soruyu ve doğru cevabı bul
        $question = Question::with('answers')->find($questionId);
        if (!$question) {
            return response()->json(['error' => 'Soru bulunamadi.'], 404);
        }

        // Sorunun videolu bir soru olup olmadığını kontrol et
        if ($question->is_video != 1) {
            return response()->json(['error' => 'Bu soru bir video sorusu değil.'], 400);
        }

        $correctAnswer = $question->answers->firstWhere('is_correct', true);

        // Kullanıcı cevabı doğru mu?
        if ($correctAnswer && $correctAnswer->id == $answerId) {
            // Kullanıcının puanını artır
            $user->point += $question->points;
            $user->save();

            // Rank ve seviyeyi güncelle
            $this->updateRanks();

            return response()->json([
                'message' => 'Doğru cevap, puan güncellendi ve sıralama güncellendi.',
                'totalPoints' => $question->points,
                'userPoint' => $user->point
            ], 200);
        } else {
            return response()->json(['message' => 'Yanlış cevap.'], 200);
        }
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
