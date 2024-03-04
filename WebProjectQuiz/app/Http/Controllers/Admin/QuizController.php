<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            // Yönetici ise, testleri listele
            $tests = $user->tests;
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

        return view('quiz.show', compact('testName', 'test'));
    }
    public function questionStore(Request $request)
    {

        // Formdan gelen verileri al
        $adminId = $request->input('admin_id');
        $testName = $request->input('test_name');
        $questions = $request->input('question_text');
        $types = $request->input('question_type');
        $difficulties = $request->input('question_difficulty');
        $answers = $request->input('answers');
        $matchingPairs= $request->input('matching_pairs');
        $correctAnswers = $request->input('correct_answer');
        $points = $request->input('question_points');
        $fileInput = $request->file('fileInput');
        // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $durationMinutes = $request->input('duration_minutes');

        // Başlangıç tarihi, bitiş tarihinden önce ve en az güncel tarih kadar olmalı
        $currentDate = now()->toDateString();
        $validatedData = $request->validate([
            'start_date' => 'required|date|after_or_equal:' . $currentDate,
            'end_date' => 'required|date|after:start_date',
            // Diğer gerekli doğrulama kuralları buraya eklenebilir
        ]);
        // dd($request->all(),$fileInput);
        // Test oluştur
        $test = new Test();
        $test->name = $testName;
        $test->admin_id = $adminId;
        $test->start_date = $startDate;
        $test->end_date = $endDate;
        $test->duration_minutes = $durationMinutes;

        // Slug oluşturma
        $slug = Str::slug($testName); // Test adından slug oluşturma

        // Aynı slug'a sahip başka bir test var mı kontrol et
        if (Test::where('slug', $slug)->exists()) {
            // Aynı slug'a sahip başka bir test varsa, eşsiz bir slug oluştur
            $slug = $slug . '-' . uniqid();
        }

        $test->slug = $slug; // Oluşturulan slug'ı test modeline ekle

        // Testi kaydet
        $test->save();
        // dd($questions, $answers, $correctAnswers);
        $data =[
            "user_id" => Auth::user()->id,
            "title" => $test->name,
        ];

        // Soruları ve cevapları kaydet
        foreach ($questions as $index => $questionText) {
            // Soruyu kaydet
            $question = new Question();
            $question->test_id = $test->id;
            $question->text = $questionText;
            $question->type = $types[$index];
            $question->difficulty = $difficulties[$index];
            $question->points = $this->calculatePoints($points, $index, $question->difficulty);

            // Kullanıcı tarafından yüklenen fotoğraf dosyasını işleme
            if ($fileInput !== null) {
                $fileName = time() . '_' . $fileInput->getClientOriginalName();

                // 'admin/questionFile' dizini altına dosyayı kaydet
                $path = $fileInput->storeAs('admin/questionFile', $fileName);

                // Dosyanın yolunu soru kaydına ekleyin
                $question->media_path = $path;
                // dd($question);
            }

            $question->save();

            if ($question->type == 1) { // Çoktan seçmeli soru
               // Cevapları kaydet
               foreach ($answers as $index => $answer) {
                if (isset($answer['text']) && is_array($answer['text'])) {
                    foreach ($answer['text'] as $answerIndex => $answerText) {
                    $answer = new Answer();
                    $answer->question_id = $question->id;
                    $answer->text = $answerText;

                    // Doğru cevabı belirle
                    $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
                    $answer->is_correct = $isCorrect;

                    $answer->save();
                }}}
            } elseif ($question->type == 2) { // Eşleştirme sorusu
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
        // Slug'a göre ilgili testi bul
        $test = Test::where('slug', $slug)->firstOrFail();
        // Mevcut testin adını al
        $testName = $test->name;

        // Mevcut testin başlangıç tarihi, bitiş tarihi ve süresini al
        $startDate = $test->start_date;
        $endDate = $test->end_date;
        $durationMinutes = $test->duration_minutes;


        $durationMinutes = $test->duration_minutes;

        // Mevcut testin sorularını al ve her bir sorunun seçeneklerini ve doğru cevabını ekleyerek bir dizi oluştur
        $questions = [];
        foreach ($test->questions as $question) {
            $answers = [];
            foreach ($question->answers as $answer) {
                $answers[] = [
                    'text' => $answer->text,
                    'is_correct' => $answer->is_correct,
                ];
            }
            $questions[] = [
                'text' => $question->text,
                'type' => $question->type,
                'answers' => $answers,
                'difficulty' => $question->difficulty,
                'points' => $question->points,
            ];
        }
        // Düzenleme formunu döndür ve mevcut test verilerini iletmek
        return view('quiz.edit', compact('test', 'testName', 'startDate', 'endDate', 'durationMinutes', 'questions'))->with([
            'message' => 'TESTİNİZ GÜNCELLENDİ',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Formdan gelen verileri al
        $testId = $request->input('test_id');

        $adminId = $request->input('admin_id');
        $testName = $request->input('test_name');
        $questions = $request->input('question_text');
        $types = $request->input('question_type');
        $difficulties = $request->input('question_difficulty');
        $answers = $request->input('answers');
        $correctAnswers = $request->input('correct_answer');
        $points = $request->input('question_points');

        // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $durationMinutes = $request->input('duration_minutes');

        // Mevcut testi bul
        $test = Test::findOrFail($testId);

        // Test verilerini güncelle
        $test->admin_id = $adminId;
        $test->name = $testName;
        $test->start_date = $startDate;
        $test->end_date = $endDate;
        $test->duration_minutes = $durationMinutes;
        $test->save();

        // Mevcut soruları ve cevapları al
        $existingQuestions = $test->questions;

        // Soruları güncelle veya ekle
        foreach ($questions as $index => $questionText) {
            // Sorunun index numarasını kontrol et
            if (isset($existingQuestions[$index])) {
                // Index varsa, mevcut soruyu güncelle
                $question = $existingQuestions[$index];
                $question->text = $questionText;
                $question->type = $types[$index];
                $question->difficulty = $difficulties[$index];
                $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
                $question->save();

                // Cevapları güncelle
                foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
                    $answer = $question->answers[$answerIndex];
                    $answer->text = $answerText;

                    // Doğru cevabı güncelle
                    $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
                    $answer->is_correct = $isCorrect;
                    // dd($request);
                    // dd($question,$answers,$answer,$correctAnswers,$answerIndex,$isCorrect);
                    $answer->save();
                }
            } else {
                // Index yoksa, yeni bir soru oluştur
                $question = new Question();
                $question->test_id = $testId;
                $question->text = $questionText;
                $question->type = $types[$index];
                $question->difficulty = $difficulties[$index];
                $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
                $question->save();

                // Yeni cevapları ekle
                foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
                    $answer = new Answer();
                    $answer->question_id = $question->id;
                    $answer->text = $answerText;
                    $answer->is_correct = ($correctAnswers[$index] == $answerIndex); // Doğru cevabı güncelle
                    $answer->save();
                }
            }
        }

        // Yönlendirme ve mesaj dön
        return redirect()->route('quiz.quiz')->with([
            'message' => 'TESTİNİZ GÜNCELLENDİ',
            'alert-type' => 'success'
        ]);
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
