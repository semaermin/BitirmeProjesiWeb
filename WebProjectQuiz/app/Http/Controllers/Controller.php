<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Question;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class Controller extends BaseController
{
    public function index()
    {
        $user = User::all()->first();
        return view('dashboard', compact('user'));
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('MyAppToken')->plainTextToken;
            return response()->json(['message' => 'User logged in successfully', 'token' => $token]);
        }

        return response()->json(['message' => 'Unauthorized22'], 401);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        // Kullanıcı admin değilse, profili döndür
        if ($user->is_admin == 0) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Only non-admin users can access profile.'], 401);
        }
    }

    // public function index()
    // {
    //     $users = User::all();
    //     return response()->json($users);
    // }
    // public function quizAdd()
    // {
    //     return view('quiz.quiz');
    // }
    // public function questionStore(Request $request)
    // {
    //     $data = $request->validate([
    //         'test_id' => 'required|exists:tests,id',
    //         'questions.*.type' => 'required|string|in:multiple_choice,fill_in_the_blank,writing',
    //         'questions.*.text' => 'required|string',
    //         'questions.*.difficulty' => 'required|string|in:easy,medium,hard', // Zorluk seviyesi alanı
    //     ]);

    //     // Formdan gelen test ID'si
    //     $test_id = $data['test_id'];

    //     // Tüm soruları kaydetmek için boş bir dizi oluştur
    //     $questions = [];

    //     // Her bir soru için döngü oluştur
    //     foreach ($data['questions'] as $questionData) {
    //         $question = new Question();
    //         $question->test_id = $test_id; // Her bir soru için test ID'sini ayarla
    //         $question->type = $questionData['type'];
    //         $question->text = $questionData['text'];
    //         $question->points = $questionData['question_points'];

    //         // Diğer işlemler...
    //         $questions[] = $question; // Her bir soruyu diziye ekle

    //         // Cevapları işle
    //         if (isset($questionData['answers']) && is_array($questionData['answers'])) {
    //             foreach ($questionData['answers'] as $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;
    //                 // Doğru cevabı belirleme işlemleri buraya eklenmeli
    //                 $answer->save();
    //             }
    //         }
    //     }





    //     // Tüm soruları kaydet
    //     Question::insert($questions);

    //     return redirect()->back()->with('success', 'Sorular başarıyla teste eklendi.');
    // }
    // public function questionShow(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Başlangıç tarihi, bitiş tarihinden önce ve en az güncel tarih kadar olmalı
    //     $currentDate = now()->toDateString();
    //     $validatedData = $request->validate([
    //         'start_date' => 'required|date|after_or_equal:' . $currentDate,
    //         'end_date' => 'required|date|after:start_date',
    //         // Diğer gerekli doğrulama kuralları buraya eklenebilir
    //     ]);

    //     // Test oluştur
    //     $test = new Test();
    //     $test->name = $testName;
    //     $test->admin_id = $adminId;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;

    //     // Slug oluşturma
    //     $slug = Str::slug($testName); // Test adından slug oluşturma

    //     // Aynı slug'a sahip başka bir test var mı kontrol et
    //     if (Test::where('slug', $slug)->exists()) {
    //         // Aynı slug'a sahip başka bir test varsa, eşsiz bir slug oluştur
    //         $slug = $slug . '-' . uniqid();
    //     }

    //     $test->slug = $slug; // Oluşturulan slug'ı test modeline ekle

    //     // Testi kaydet
    //     $test->save();

    //     // Soruları ve cevapları kaydet
    //     foreach ($questions as $index => $questionText) {
    //         $question = new Question();
    //         $question->test_id = $test->id;
    //         $question->text = $questionText;
    //         $question->type = $types[$index];
    //         $question->difficulty = $difficulties[$index];


    //         // Puan alanı kontrolü yap
    //         if (!isset($points[$index]) || empty($points[$index]) || $points[$index] > 10) {
    //             // Puan girilmemişse, boşsa veya 10'dan fazla ise, zorluk seviyesine göre varsayılan bir puan ata
    //             switch ($question->difficulty) {
    //                 case 'easy':
    //                     $question->points = 1;
    //                     break;
    //                 case 'medium':
    //                     $question->points = 2;
    //                     break;
    //                 case 'hard':
    //                     $question->points = 3;
    //                     break;
    //                 default:
    //                     $question->points = 2; // Varsayılan olarak medium seviyesi için 2 puan ata
    //                     break;
    //             }
    //         } else {
    //             // Puan değeri formdan gelen veriye göre ayarlanmışsa, bu değeri kullan
    //             $question->points = $points[$index];
    //         }

    //         $question->save();
    //         dd($answers[$index]['text']);
    //         // Soruya ait cevapları kaydet
    //         if (isset($answers[$index]['text']) && isset($correctAnswers[$index]) && is_array($correctAnswers[$index])) {
    //             dd($questions,$answers, $correctAnswers);
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;

    //                 // Doğru cevabı belirlemek için indexleri kontrol et
    //                 $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->is_correct = $isCorrect;
    //                 dd($isCorrect,$question);

    //                 // Cevabı kaydet
    //                 $answer->save();
    //             }
    //         }
    //     }


    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla oluşturuldu!');
    // }
    // public function questionShow(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Başlangıç tarihi, bitiş tarihinden önce ve en az güncel tarih kadar olmalı
    //     $currentDate = now()->toDateString();
    //     $validatedData = $request->validate([
    //         'start_date' => 'required|date|after_or_equal:' . $currentDate,
    //         'end_date' => 'required|date|after:start_date',
    //         // Diğer gerekli doğrulama kuralları buraya eklenebilir
    //     ]);

    //     // Test oluştur
    //     $test = new Test();
    //     $test->name = $testName;
    //     $test->admin_id = $adminId;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;

    //     // Slug oluşturma
    //     $slug = Str::slug($testName); // Test adından slug oluşturma

    //     // Aynı slug'a sahip başka bir test var mı kontrol et
    //     if (Test::where('slug', $slug)->exists()) {
    //         // Aynı slug'a sahip başka bir test varsa, eşsiz bir slug oluştur
    //         $slug = $slug . '-' . uniqid();
    //     }

    //     $test->slug = $slug; // Oluşturulan slug'ı test modeline ekle

    //     // Testi kaydet
    //     $test->save();

    //     // Soruları ve cevapları kaydet
    //     foreach ($questions as $index => $questionText) {
    //         // Soruyu kaydet
    //         $question = new Question();
    //         $question->test_id = $test->id;
    //         $question->text = $questionText;
    //         $question->type = $types[$index];
    //         $question->difficulty = $difficulties[$index];

    //         // Puan alanı kontrolü yap
    //         $question->points = $this->calculatePoints($points, $index, $question->difficulty);

    //         $question->save();

    //         foreach ($answers as $questionId => $questionAnswers) {
    //             // Eğer cevaplar dizisi ve belirli bir indeks numarasına sahipse, cevapları kaydet
    //             if (isset($answers[$index]['text']) && is_array($answers[$index]['text'])) {
    //                 foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                     if (isset($answerText)) { // 'text' dizisinin içeriğini kontrol et
    //                         $answer = new Answer();
    //                         $answer->question_id = $questionId;
    //                         $answer->text = $answerText;

    //                         // Doğru cevabı belirle
    //                         $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                         $answer->is_correct = $isCorrect;

    //                         // Cevabı kaydet
    //                         $answer->save();
    //                     }
    //                 }
    //             }
    //         }
    //     }


    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla oluşturuldu!');
    // }
    // public function questionShow(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Başlangıç tarihi, bitiş tarihinden önce ve en az güncel tarih kadar olmalı
    //     $currentDate = now()->toDateString();
    //     $validatedData = $request->validate([
    //         'start_date' => 'required|date|after_or_equal:' . $currentDate,
    //         'end_date' => 'required|date|after:start_date',
    //         // Diğer gerekli doğrulama kuralları buraya eklenebilir
    //     ]);

    //     // Test oluştur
    //     $test = new Test();
    //     $test->name = $testName;
    //     $test->admin_id = $adminId;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;

    //     // Slug oluşturma
    //     $slug = Str::slug($testName); // Test adından slug oluşturma

    //     // Aynı slug'a sahip başka bir test var mı kontrol et
    //     if (Test::where('slug', $slug)->exists()) {
    //         // Aynı slug'a sahip başka bir test varsa, eşsiz bir slug oluştur
    //         $slug = $slug . '-' . uniqid();
    //     }

    //     $test->slug = $slug; // Oluşturulan slug'ı test modeline ekle

    //     // Testi kaydet
    //     $test->save();
    //     // dd($questions, $answers, $correctAnswers);
    //     // Soruları ve cevapları kaydet
    //     foreach ($questions as $index => $questionText) {
    //         // Soruyu kaydet
    //         $question = new Question();
    //         $question->test_id = $test->id;
    //         $question->text = $questionText;
    //         $question->type = $types[$index];
    //         $question->difficulty = $difficulties[$index];
    //         $question->points = $this->calculatePoints($points, $index, $question->difficulty);
    //         $question->save();

    //         // Cevapları kaydet
    //         foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //             $answer = new Answer();
    //             $answer->question_id = $question->id;
    //             $answer->text = $answerText;

    //             // Doğru cevabı belirle
    //             $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //             $answer->is_correct = $isCorrect;

    //             $answer->save();
    //         }
    //     }


    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla oluşturuldu!');
    // }

    // // Puanı hesapla
    // private function calculatePoints($points, $index, $difficulty)
    // {
    //     if (!isset($points[$index]) || empty($points[$index]) || $points[$index] > 10) {
    //         // Puan girilmemişse, boşsa veya 10'dan fazla ise, zorluk seviyesine göre varsayılan bir puan ata
    //         switch ($difficulty) {
    //             case 'easy':
    //                 return 1;
    //             case 'medium':
    //                 return 2;
    //             case 'hard':
    //                 return 3;
    //             default:
    //                 return 2; // Varsayılan olarak medium seviyesi için 2 puan ata
    //         }
    //     }

    //     // Puan değeri formdan gelen veriye göre ayarlanmışsa, bu değeri kullan
    //     return $points[$index];
    // }

    // public function index()
    // {
    //     $tests = Test::all();
    //     return view('dashboard', compact('tests'));
    // }

    // public function index()
    // {
    //     // Aktif kullanıcıyı al
    //     $user = auth()->user();
    //     // dd($user);

    //     // Kullanıcının yönetici olup olmadığını kontrol et
    //     if ($user && $user->isAdmin()) {
    //         // Yönetici ise, testleri listele
    //         $tests = $user->tests;
    //         return view('dashboard', compact('tests'))->with('message', 'Başarıyla yükledin')->with('alert-type', 'success');

    //     } else {
    //         // Yönetici değilse, anasayfaya yönlendir ve hata mesajı göster
    //         // return abort(403, 'Unauthorized');
    //         return redirect()->route('quiz.add')->with('message', 'Bu sayfaya erişmek için yetkiniz yok.')->with('alert-type', 'error');
    //         //toastr denedin olmadı
    //     }
    // }


    // public function quizShow($slug)
    // {
    //     // Slug'a göre ilgili testi bul
    //     $test = Test::where('slug', $slug)->firstOrFail();

    //     // Mevcut testin adını al
    //     $testName = $test->name;

    //     return view('quiz.show', compact('testName', 'test'));
    // }

    // public function quizEdit($slug)
    // {
    //     // Slug'a göre ilgili testi bul
    //     $test = Test::where('slug', $slug)->firstOrFail();

    //     // Mevcut testin adını al
    //     $testName = $test->name;

    //     // Mevcut testin sorularını al ve her bir sorunun seçeneklerini ve doğru cevabını ekleyerek bir dizi oluştur
    //     $questions = [];
    //     foreach ($test->questions as $question) {
    //         $answers = [];
    //         foreach ($question->answers as $answer) {
    //             $answers[] = [
    //                 'text' => $answer->text,
    //                 'is_correct' => $answer->is_correct,
    //             ];
    //         }
    //         $questions[] = [
    //             'text' => $question->text,
    //             'type' => $question->type,
    //             'answers' => $answers,
    //         ];
    //     }
    //     // Düzenleme formunu döndür ve mevcut test verilerini iletmek
    //     return view('quiz.edit', compact('test','testName', 'questions'));
    // }




    // public function quizEdit($slug)
    // {

    //     // Slug'a göre ilgili testi bul
    //     $test = Test::where('slug', $slug)->firstOrFail();
    //     // Mevcut testin adını al
    //     $testName = $test->name;

    //     // Mevcut testin başlangıç tarihi, bitiş tarihi ve süresini al
    //     $startDate = $test->start_date;
    //     $endDate = $test->end_date;
    //     $durationMinutes = $test->duration_minutes;


    //     $durationMinutes = $test->duration_minutes;

    //     // Mevcut testin sorularını al ve her bir sorunun seçeneklerini ve doğru cevabını ekleyerek bir dizi oluştur
    //     $questions = [];
    //     foreach ($test->questions as $question) {
    //         $answers = [];
    //         foreach ($question->answers as $answer) {
    //             $answers[] = [
    //                 'text' => $answer->text,
    //                 'is_correct' => $answer->is_correct,
    //             ];
    //         }
    //         $questions[] = [
    //             'text' => $question->text,
    //             'type' => $question->type,
    //             'answers' => $answers,
    //             'difficulty' => $question->difficulty,
    //             'points' => $question->points,
    //         ];
    //     }
    //     // Düzenleme formunu döndür ve mevcut test verilerini iletmek
    //     return view('quiz.edit', compact('test', 'testName', 'startDate', 'endDate', 'durationMinutes', 'questions'));
    // }



    // public function quizUpdate(Request $request, $slug)
    // {
    //     // Formdan gelen verileri doğrula
    //     $validatedData = $request->validate([
    //         'test_name' => 'required|string|max:255',
    //         'question_text.*' => 'required|string', // Örnek doğrulama kuralı
    //         'question_type.*' => 'required|in:1,2', // Soru tipi doğrulama kuralı
    //         'answers.*.text.*' => 'required|string', // Örnek doğrulama kuralı
    //         'correct_answer.*' => 'required|string', // Örnek doğrulama kuralı
    //         'question_points.*' => 'nullable|integer|min:0', // Puanlama doğrulama kuralı
    //     ]);

    //     // Test nesnesini slug değerinden al
    //     $test = Test::where('slug', $slug)->firstOrFail();

    //     // Testi güncelle
    //     $test->update([
    //         'name' => $validatedData['test_name'],
    //         // İstenirse diğer güncellenecek alanlar buraya eklenebilir
    //     ]);

    //     // Mevcut soruları güncelle veya ekle
    //     foreach ($validatedData['question_text'] as $index => $questionText) {
    //         if (isset($test->questions[$index])) {
    //             // Soru varsa güncelle
    //             $question = $test->questions[$index];
    //             $question->update([
    //                 'text' => $questionText,
    //                 'type' => $validatedData['question_type'][$index], // Örnek olarak tipi de güncelle
    //                 'points' => $validatedData['question_points'][$index], // Puanlamayı güncelle
    //                 'difficulty' => $validatedData['question_difficulty'][$index], // Zorluk seviyesini güncelle
    //             ]);
    //         } else {
    //             // Soru yoksa ekle
    //             $question = $test->questions()->create([
    //                 'text' => $questionText,
    //                 'type' => $validatedData['question_type'][$index], // Örnek olarak tipi de ekleyin
    //                 'test_id' => $test->id, // Test ID'sini ekleyin
    //                 'points' => $validatedData['question_points'][$index], // Puanlamayı ekleyin
    //                 'difficulty' => $validatedData['question_difficulty'][$index], // Zorluk seviyesini ekleyin
    //             ]);
    //         }

    //         // Soruya ait cevapları güncelle veya ekle
    //         foreach ($validatedData['answers'][$index]['text'] as $answerIndex => $answerText) {
    //             if (isset($question->answers[$answerIndex])) {
    //                 // Cevap varsa güncelle
    //                 $answer = $question->answers[$answerIndex];
    //                 $isCorrect = $answerText === $validatedData['correct_answer'][$index];
    //                 $answer->update([
    //                     'text' => $answerText,
    //                     'is_correct' => $isCorrect ? 1 : 0, // Doğru cevabı kontrol et ve güncelle
    //                 ]);

    //             } else {
    //                 // Cevap yoksa ekle
    //                 $question->answers()->create([
    //                     'text' => $answerText,
    //                     'is_correct' => $answerText === $validatedData['correct_answer'][$index] ? 1 : 0, // Doğru cevabı kontrol et ve ekle
    //                     'question_id' => $question->id, // Soru ID'sini ekleyin
    //                 ]);
    //             }
    //         }
    //     }

    //     // Başarı mesajı döndür ve yönlendir
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request, $id)
    // {
    //     // Formdan gelen verileri al
    //     $validatedData = $request->validate([
    //         'test_name' => 'required|string',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date|after:start_date',
    //         'duration_minutes' => 'required|integer',
    //         // Diğer gerekli doğrulama kuralları buraya eklenebilir
    //     ]);

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($id);

    //     // Test bilgilerini güncelle
    //     $test->name = $request->input('test_name');
    //     $test->start_date = $request->input('start_date');
    //     $test->end_date = $request->input('end_date');
    //     $test->duration_minutes = $request->input('duration_minutes');
    //     // Diğer test alanlarını da güncelleyebilirsiniz

    //     // Testi kaydet
    //     $test->save();

    //     // Mevcut soruları güncelle, yeni soruları ekle ve silinmiş soruları kaldır
    //     foreach ($request->input('questions') as $questionData) {
    //         $question = Question::updateOrCreate(
    //             ['id' => $questionData['id']], // Eğer ID varsa güncelle, yoksa oluştur
    //             [
    //                 'test_id' => $test->id,
    //                 'text' => $questionData['text'],
    //                 'type' => $questionData['type'],
    //                 'difficulty' => $questionData['difficulty'],
    //                 'points' => $questionData['points'],
    //                 // Diğer soru alanlarını da güncelleyebilirsiniz
    //             ]
    //         );

    //         // Mevcut cevapları güncelle, yeni cevapları ekle ve silinmiş cevapları kaldır
    //         foreach ($questionData['answers'] as $answerData) {
    //             Answer::updateOrCreate(
    //                 ['id' => $answerData['id']], // Eğer ID varsa güncelle, yoksa oluştur
    //                 [
    //                     'question_id' => $question->id,
    //                     'text' => $answerData['text'],
    //                     'is_correct' => $answerData['is_correct'],
    //                     // Diğer cevap alanlarını da güncelleyebilirsiniz
    //                 ]
    //             );
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request)
    // {
    //     // formdan gelen veriler is_correct değri dışında hatalı
    //     // dd($request -> all());
    //     // Formdan gelen verileri al
    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');


    //     // Test oluştur veya güncelle
    //     $test = Test::updateOrCreate(
    //         ['name' => $testName],
    //         [
    //             'admin_id' => $adminId,
    //             'start_date' => $startDate,
    //             'end_date' => $endDate,
    //             'duration_minutes' => $durationMinutes,
    //         ]
    //     );

    //     // dd($request, $test, $questions, $answers, $correctAnswers);
    //     // Soruları ve cevapları kaydet
    //     foreach ($questions as $index => $questionText) {
    //         // Soruyu kaydet
    //         $question = new Question();
    //         $question->test_id = $test->id;
    //         $question->text = $questionText;
    //         $question->type = $types[$index];
    //         $question->difficulty = $difficulties[$index];
    //         $question->points = $this->calculatePoints($points, $index, $question->difficulty);
    //         $question->save();

    //         // Cevapları kaydet
    //         foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //             $answer = new Answer();
    //             $answer->question_id = $question->id;
    //             $answer->text = $answerText;

    //             // Doğru cevabı belirle
    //             $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //             $answer->is_correct = $isCorrect;

    //             $answer->save();
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $testId = $request->input('test_id');

    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($testId);

    //     // Test verilerini güncelle
    //     $test->admin_id = $adminId;
    //     $test->name = $testName;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;
    //     $test->save();

    //     // Soruları ve cevapları güncelle veya ekle
    //     foreach ($questions as $index => $questionText) {
    //         // Mevcut soruyu bul
    //         $existingQuestion = Question::where('test_id', $testId)->where('text', $questionText)->first();

    //         if ($existingQuestion) {
    //             // Soruyu güncelle
    //             $question = $existingQuestion;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();
    //         } else {
    //             // Yeni soru oluştur
    //             $question = new Question();
    //             $question->test_id = $testId;
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();
    //         }

    //         // Cevapları güncelle veya ekle
    //         foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //             $existingAnswer = Answer::where('question_id', $question->id)->where('text', $answerText)->first();

    //             if ($existingAnswer) {
    //                 // Cevabı güncelle
    //                 $answer = $existingAnswer;
    //                 $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             } else {
    //                 // Yeni cevap oluştur
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;
    //                 $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $testId = $request->input('test_id');

    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($testId);

    //     // Test verilerini güncelle
    //     $test->admin_id = $adminId;
    //     $test->name = $testName;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;
    //     $test->save();

    //     // Mevcut soruların ve cevaplarının ID'lerini al
    //     $existingQuestionIds = $test->questions()->pluck('id')->toArray();

    //     // Soruları güncelle veya ekle
    //     foreach ($questions as $index => $questionText) {
    //         // Sorunun index numarasını kontrol et
    //         if (isset($existingQuestionIds[$index])) {
    //             // Index varsa, mevcut soruyu güncelle
    //             $question = Question::findOrFail($existingQuestionIds[$index]);
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Cevapları güncelle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = Answer::where('question_id', $question->id)->where('text', $answerText)->first();
    //                 if ($answer) {
    //                     $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                     $answer->save();
    //                 }
    //             }
    //         } else {
    //             // Index yoksa, yeni bir soru oluştur
    //             $question = new Question();
    //             $question->test_id = $testId;
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Yeni cevapları ekle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;
    //                 $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $testId = $request->input('test_id');

    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($testId);

    //     // Test verilerini güncelle
    //     $test->admin_id = $adminId;
    //     $test->name = $testName;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;
    //     $test->save();

    //     // Mevcut soruların ve cevaplarının ID'lerini al
    //     $existingQuestionIds = $test->questions()->pluck('id')->toArray();

    //     // Soruları güncelle veya ekle
    //     foreach ($questions as $index => $questionText) {
    //         // Sorunun index numarasını kontrol et
    //         if (isset($existingQuestionIds[$index])) {
    //             // Index varsa, mevcut soruyu güncelle
    //             $question = Question::findOrFail($existingQuestionIds[$index]);
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Cevapları güncelle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = Answer::where('question_id', $question->id)->where('text', $answerText)->first();
    //                 if ($answer) {
    //                     $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                     $answer->save();
    //                 }
    //             }

    //             // Doğru cevabı güncelle
    //             if (isset($correctAnswers[$index])) {
    //                 $correctAnswerIndex = $correctAnswers[$index];
    //                 $correctAnswer = Answer::where('question_id', $question->id)->where('text', $answers[$index]['text'][$correctAnswerIndex])->first();
    //                 if ($correctAnswer) {
    //                     $correctAnswer->is_correct = true;
    //                     $correctAnswer->save();
    //                 }
    //             }
    //         } else {
    //             // Index yoksa, yeni bir soru oluştur
    //             $question = new Question();
    //             $question->test_id = $testId;
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Yeni cevapları ekle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;
    //                 $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $testId = $request->input('test_id');

    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($testId);

    //     // Test verilerini güncelle
    //     $test->admin_id = $adminId;
    //     $test->name = $testName;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;
    //     $test->save();

    //     // Mevcut soruları al
    //     $existingQuestions = $test->questions;

    //     // Soruları güncelle veya ekle
    //     foreach ($questions as $index => $questionText) {
    //         // Sorunun index numarasını kontrol et
    //         if (isset($existingQuestions[$index])) {
    //             // Index varsa, mevcut soruyu güncelle
    //             $question = $existingQuestions[$index];
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Mevcut sorunun cevaplarını al
    //             $existingAnswers = $question->answers;

    //             // Cevapları güncelle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = $existingAnswers[$answerIndex];
    //                 $answer->text = $answerText;
    //                 $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }

    //             // Doğru cevabı güncelle
    //             if (isset($correctAnswers[$index])) {
    //                 $correctAnswerIndex = $correctAnswers[$index];
    //                 $correctAnswer = $existingAnswers[$correctAnswerIndex];
    //                 $correctAnswer->is_correct = true;
    //                 $correctAnswer->save();
    //             }
    //         } else {
    //             // Index yoksa, yeni bir soru oluştur
    //             $question = new Question();
    //             $question->test_id = $testId;
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Yeni cevapları ekle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;
    //                 $answer->is_correct = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }
    // public function quizUpdate(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $testId = $request->input('test_id');

    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($testId);

    //     // Test verilerini güncelle
    //     $test->admin_id = $adminId;
    //     $test->name = $testName;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;
    //     $test->save();

    //     // Mevcut soruları al
    //     $existingQuestions = $test->questions;

    //     // Soruları güncelle veya ekle
    //     foreach ($questions as $index => $questionText) {
    //         // Sorunun index numarasını kontrol et
    //         if (isset($existingQuestions[$index])) {
    //             // Index varsa, mevcut soruyu güncelle
    //             $question = $existingQuestions[$index];
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Mevcut sorunun cevaplarını al
    //             $existingAnswers = $question->answers;

    //             // Cevapları güncelle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = $existingAnswers[$answerIndex];
    //                 $answer->text = $answerText;

    //                 // Doğru cevapı kontrol et ve güncelle
    //                 $answer->is_correct = $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }
    //         } else {
    //             // Index yoksa, yeni bir soru oluştur
    //             $question = new Question();
    //             $question->test_id = $testId;
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Yeni cevapları ekle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;

    //                 // Doğru cevapı kontrol et ve güncelle
    //                 $answer->is_correct = $correctAnswers[$index] == $answerIndex;
    //                 $answer->save();
    //             }
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }

    // public function quizUpdate(Request $request)
    // {
    //     // Formdan gelen verileri al
    //     $testId = $request->input('test_id');

    //     $adminId = $request->input('admin_id');
    //     $testName = $request->input('test_name');
    //     $questions = $request->input('question_text');
    //     $types = $request->input('question_type');
    //     $difficulties = $request->input('question_difficulty');
    //     $answers = $request->input('answers');
    //     $correctAnswers = $request->input('correct_answer');
    //     $points = $request->input('question_points');

    //     // Yeni alanlar: başlangıç tarihi, bitiş tarihi ve süre
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');
    //     $durationMinutes = $request->input('duration_minutes');

    //     // Mevcut testi bul
    //     $test = Test::findOrFail($testId);

    //     // Test verilerini güncelle
    //     $test->admin_id = $adminId;
    //     $test->name = $testName;
    //     $test->start_date = $startDate;
    //     $test->end_date = $endDate;
    //     $test->duration_minutes = $durationMinutes;
    //     $test->save();

    //     // Mevcut soruları ve cevapları al
    //     $existingQuestions = $test->questions;

    //     // Soruları güncelle veya ekle
    //     foreach ($questions as $index => $questionText) {
    //         // Sorunun index numarasını kontrol et
    //         if (isset($existingQuestions[$index])) {
    //             // Index varsa, mevcut soruyu güncelle
    //             $question = $existingQuestions[$index];
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Cevapları güncelle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = $question->answers[$answerIndex];
    //                 $answer->text = $answerText;

    //                 // Doğru cevabı güncelle
    //                 $isCorrect = isset($correctAnswers[$index]) && $correctAnswers[$index] == $answerIndex;
    //                 $answer->is_correct = $isCorrect;
    //                 // dd($request);
    //                 // dd($question,$answers,$answer,$correctAnswers,$answerIndex,$isCorrect);
    //                 $answer->save();
    //             }
    //         } else {
    //             // Index yoksa, yeni bir soru oluştur
    //             $question = new Question();
    //             $question->test_id = $testId;
    //             $question->text = $questionText;
    //             $question->type = $types[$index];
    //             $question->difficulty = $difficulties[$index];
    //             $question->points = $this->calculatePoints($points, $index, $difficulties[$index]);
    //             $question->save();

    //             // Yeni cevapları ekle
    //             foreach ($answers[$index]['text'] as $answerIndex => $answerText) {
    //                 $answer = new Answer();
    //                 $answer->question_id = $question->id;
    //                 $answer->text = $answerText;
    //                 $answer->is_correct = ($correctAnswers[$index] == $answerIndex); // Doğru cevabı güncelle
    //                 $answer->save();
    //             }
    //         }
    //     }

    //     // Yönlendirme ve mesaj dön
    //     return redirect()->route('dashboard')->with('success', 'Test başarıyla güncellendi!');
    // }






















}
