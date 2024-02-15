<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use App\Models\MatchingOption;

class TestSeeder extends Seeder
{
    public function run()
    {
        // Test verileri
        $tests = [
            [
                'name' => 'Matematik Testi',
                'slug' => 'matematik-testi',
                'admin_id' => 1, // Örnek bir admin ID
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'duration_minutes' => 60,
            ],
            // İhtiyaç duyulan diğer testler buraya eklenebilir
        ];

        // Sorular ve cevapları
        $questions = [
            [
                'test_id' => 1, // Testin ID'si
                'text' => '2 + 2 kaçtır?',
                'type' => '1', // Tek seçenekli
                'difficulty' => 'easy',
                'points' => 2,
                'media_path' => null,
                'answers' => [
                    ['text' => '3', 'is_correct' => 0],
                    ['text' => '4', 'is_correct' => 1],
                    ['text' => '5', 'is_correct' => 0],
                    ['text' => '6', 'is_correct' => 0],
                ]
            ],
            [
                'test_id' => 1, // Testin ID'si
                'text' => 'Hangi ülke hangi başkente denk gelir?',
                'type' => '2', // Eşleştirme
                'difficulty' => 'medium',
                'points' => 3,
                'media_path' => null,
                'matchingOptions' => [
                    ['text' => 'Türkiye', 'pair_order' => 1],
                    ['text' => 'Türkiye', 'pair_order' => 1],
                    ['text' => 'Fransa', 'pair_order' => 2],
                    ['text' => 'Fransa', 'pair_order' => 2],
                    ['text' => 'Almanya', 'pair_order' => 3],
                    ['text' => 'Almanya', 'pair_order' => 3],
                ]
            ],
            // İhtiyaç duyulan diğer sorular buraya eklenebilir
        ];

        foreach ($tests as $testData) {
            // Test oluşturma
            $test = Test::create($testData);

            foreach ($questions as $questionData) {
                // Soru oluşturma
                $question = Question::create([
                    'test_id' => $test->id,
                    'text' => $questionData['text'],
                    'type' => $questionData['type'],
                    'difficulty' => $questionData['difficulty'],
                    'points' => $questionData['points'],
                    'media_path' => $questionData['media_path'],
                ]);

                if ($questionData['type'] === '2') {
                    // Eşleştirme seçeneklerini oluşturma
                    foreach ($questionData['matchingOptions'] as $matchingOptionData) {
                        MatchingOption::create([
                            'question_id' => $question->id,
                            'option_text' => $matchingOptionData['text'],
                            'pair_order' => $matchingOptionData['pair_order'],
                        ]);
                    }
                } else {
                    // Diğer soruların cevaplarını oluşturma
                    foreach ($questionData['answers'] as $answerData) {
                        Answer::create([
                            'question_id' => $question->id,
                            'text' => $answerData['text'],
                            'is_correct' => $answerData['is_correct'],
                        ]);
                    }
                }
            }
        }
    }
}
