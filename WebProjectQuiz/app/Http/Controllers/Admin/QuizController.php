<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use App\Models\Media;
use App\Models\MatchingOption;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Aktif kullanıcıyı al
        $user = auth()->user();

        // Kullanıcının yönetici olup olmadığını kontrol et
        if ($user && $user->is_admin) {
            // Yönetici ise, testleri listele son 10 tane
            $tests = $user->tests()->latest()->take(10)->get();
            return view('quiz.quiz', [
                'tests' => $tests,
                'message' => 'Başarıyla yükledin',
                'alert-type' => 'success',
            ]);
        }
         else {
            // Yönetici değilse, anasayfaya yönlendir ve hata mesajı göster
            // return abort(403, 'Unauthorized');
            return redirect()->route('dashboard')
            ->with([
                'message' => 'Bu sayfaya erişmek için yetkiniz yok.',
                'alert-type' => 'error',
            ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('quiz.create');
    }

    /**
     * Display the specified resource.
     */
    public function quizShow($slug)
    {
        // Slug'a göre ilgili testi bul
        $test = Test::where('slug', $slug)->with('questions.matchingOptions')->firstOrFail();

        // Mevcut testin adını al
        $testName = $test->name;
        // dd($test , $testName);
        return view('quiz.show', compact('testName', 'test'));
    }

    //Test oluşturma
    public function questionStore(Request $request)
    {
        // dd($request->all());
        // Formdan gelen verileri al
        $adminId = $request->input('admin_id');
        $testName = $request->input('test_name');
        $language_level = $request->input('language_level');
        $learning_purpose = $request->input('learning_purpose');
        $questions = $request->input('question_text');
        $types = $request->input('question_type', []); // Boş array varsayılan
        $difficulties = $request->input('question_difficulty');
        $answers = $request->input('answers');
        $matchingPairs = $request->input('matching_pairs');
        $correctAnswers = $request->input('correct_answer');
        $points = $request->input('question_points');
        $imageInputs = $request->file('imageInput');
        $videoInputs = $request->file('videoInput');
        $durationMinutes = $request->input('duration_minutes');

        // Test oluştur
        $test = new Test();
        $test->name = $testName;
        $test->admin_id = $adminId;
        $test->language_level = $language_level;
        $test->learning_purpose = $learning_purpose;
        $test->duration_minutes = $durationMinutes;

        // Slug oluşturma
        $slug = Str::slug($testName); // Test adından slug oluşturma

        // Aynı slug'a sahip başka bir test var mı kontrol et
        if (Test::where('slug', $slug)->exists()) {
            // Aynı slug'a sahip başka bir test varsa, eşsiz bir slug oluştur
            $slug = $slug . '-' . uniqid();
        }

        $test->slug = $slug;

        // Test save
        $test->save();

        // Soruları ve cevapları kaydet
        foreach ($questions as $index => $questionText) {
            // question save
            $question = new Question();
            $question->test_id = $test->id;
            $question->text = $questionText;

            // question type
            if (isset($types[$index])) {
                $question->type = $types[$index];
            } else {
                // Varsayılan bir tür atanabilir veya hata yönetimi yapılabilir
                return redirect()->back()->withErrors(['question_type' => 'Soru tipi belirtilmemiş.']);
            }

            // question difficulty
            if (isset($difficulties[$index])) {
                $question->difficulty = $difficulties[$index];
            } else {
                return redirect()->back()->withErrors(['question_difficulty' => 'Soru zorluk derecesi belirtilmemiş.']);
            }

            $question->points = $this->calculatePoints($points, $index, $question->difficulty);

            // Formdan gelen medya dosyalarını kontrol et
            if (isset($imageInputs[$index])) {
                // Resim dosyası varsa
                $imageInput = $imageInputs[$index];
                $fileName = time() . '_' . $imageInput->getClientOriginalName();
                $path = $imageInput->storeAs('admin/questionFile/photos', $fileName, 'public');
                $question->media_path = 'admin/questionFile/photos/' . $fileName; // sadece dosya adını kaydet
            } elseif (isset($videoInputs[$index])) {
                // Video dosyası varsa
                $videoInput = $videoInputs[$index];
                $fileName = time() . '_' . $videoInput->getClientOriginalName();
                $path = $videoInput->storeAs('admin/questionFile/videos', $fileName, 'public'); // storage dizinine kaydet
                $question->media_path = $path; // dosyanın yolu veritabanına kaydedilir
            }

            // question save in database
            $question->save();

            if ($question->type == 1) { // Çoktan seçmeli soru
                if (isset($answers[$index]['text']) && is_array($answers[$index]['text'])) {
                    foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
                        $answerModel = new Answer();
                        $answerModel->question_id = $question->id;
                        $answerModel->text = $answerText;

                        // Doğru cevapları doğru sırayla al
                        $isCorrect = isset($correctAnswers[$index]) && $answerIndex == $correctAnswers[$index];
                        $answerModel->is_correct = $isCorrect;

                        $answerModel->save();
                    }
                }
            }
            elseif ($question->type == 2) { // Eşleştirme sorusu
                foreach ($matchingPairs as $pairIndex => $pairs) {
                    // Her bir $pairs öğesinin alt dizilere sahip olup olmadığını kontrol edin
                    if (is_array($pairs)) {
                        // Alt dizi varsa, iç içe geçmiş foreach döngüsüyle içeriğe erişin
                        foreach ($pairs as $pairText) {
                            // Döngü içinde gerekli işlemleri yapın
                            $matchingOption = new MatchingOption();
                            $matchingOption->question_id = $question->id;
                            $matchingOption->option_text = $pairText;
                            $matchingOption->pair_order = $pairIndex + 1;

                            $matchingOption->save();
                        }
                    } else {
                        // Alt dizi yoksa, hata mesajı gönderin veya hatayı başka şekilde yönetin
                    }
                }
            }

        }

        // Yönlendirme ve mesaj dön
        return redirect()->route('quiz.quiz')->with([
            'message' => 'TESTİNİZ KAYDEDİLDİ',
            'alert-type' => 'success'
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $test = Test::where('slug', $slug)->firstOrFail();
        $testName = $test->name;
        $durationMinutes = $test->duration_minutes;

        $questions = [];
        foreach ($test->questions as $question) {
            $answers = [];
            foreach ($question->answers as $answer) {
                $answers[] = [
                    'text' => $answer->text,
                    'is_correct' => $answer->is_correct,
                ];
            }

            $matchingPairs = [];
            if ($question->type == 2) {
                $matchingOptions = MatchingOption::where('question_id', $question->id)->orderBy('pair_order')->get();
                $currentPair = [];
                foreach ($matchingOptions as $option) {
                    if (empty($currentPair)) {
                        // Eğer currentPair boşsa sol eşi ekle
                        $currentPair['left'] = $option->option_text;
                    } else {
                        // Eğer currentPair doluysa sağ eşi ekle
                        $currentPair['right'] = $option->option_text;
                        // Mevcut çifti matchingPairs dizisine ekle
                        $matchingPairs[] = $currentPair;
                        // currentPair'i temizle
                        $currentPair = [];
                    }
                }
            }

            $questions[] = [
                'text' => $question->text,
                'type' => $question->type,
                'answers' => $answers,
                'matching_pairs' => $matchingPairs,
                'difficulty' => $question->difficulty,
                'points' => $question->points,
            ];
        }

        return view('quiz.edit-copy', compact('test', 'testName', 'durationMinutes', 'questions'))->with([
            'message' => 'TESTİNİZ GÜNCELLENDİ',
            'alert-type' => 'success'
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $test = Test::where('slug', $slug)->firstOrFail();

        // Update the test details
        $test->update([
            'name' => $request->input('test_name'),
            'duration_minutes' => $request->input('duration_minutes'),
            'learning_purpose' => $request->input('learning_purpose'),
            'language_level' => $request->input('language_level'),
            'admin_id' => $request->input('admin_id'),
        ]);

        // Handle existing questions
        $existingQuestionIds = $request->input('existing_question_ids', []);
        $existingQuestions = Question::where('test_id', $test->id)->whereIn('id', $existingQuestionIds)->get();

        foreach ($existingQuestions as $index => $question) {
            $question->update([
                'text' => $request->input('question_text')[$index],
                'difficulty' => $request->input('question_difficulty')[$index],
                'points' => $request->input('question_points')[$index],
            ]);

            // Update answers for the existing question
            foreach ($question->answers as $answerIndex => $answer) {
                $answer->update([
                    'text' => $request->input('multiple_choice_answers')[$index][$answerIndex],
                    'is_correct' => in_array($answerIndex, $request->input('correct_answers')[$index]),
                ]);
            }
        }

        // Handle new questions
        $newQuestions = $request->input('new_questions', []);
        foreach ($newQuestions as $index => $newQuestion) {
            $question = Question::create([
                'test_id' => $test->id,
                'text' => $newQuestion['text'],
                'difficulty' => $newQuestion['difficulty'],
                'points' => $newQuestion['points'],
            ]);

            // Add answers for the new question
            foreach ($newQuestion['answers'] as $answerIndex => $answerText) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $answerText,
                    'is_correct' => in_array($answerIndex, $newQuestion['correct_answers']),
                ]);
            }
        }

        return redirect()->route('quiz.show', ['slug' => $slug])->with('success', 'Test updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Puanı hesapla
    private function calculatePoints($points, $index, $difficulty)
    {
        if (!isset($points[$index]) || empty($points[$index]) || $points[$index] > 10) {
            // Puan girilmemişse, boşsa veya 10'dan fazla ise, zorluk seviyesine göre varsayılan bir puan ata
            switch ($difficulty) {
                case 'easy':
                    return 1;
                case 'medium':
                    return 2;
                case 'hard':
                    return 3;
                default:
                    return 2; // Varsayılan olarak medium seviyesi için 2 puan ata
            }
        }

        // Puan değeri formdan gelen veriye göre ayarlanmışsa, bu değeri kullan
        return $points[$index];
    }
}
