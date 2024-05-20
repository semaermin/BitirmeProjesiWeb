<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12 text-white">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <!-- Test Oluşturma Formu -->
                <div class="p-3 text-white card bg-dark">
                    <form method="POST" action="{{ route('quiz.update', ['slug' => $test->slug]) }}" onsubmit="return validateForm()">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="test_id" value="{{ $test->id }}">
                        <!-- Test Adı Girişi -->
                        <div class="form-group">
                            <label for="test_name">Test Adı:</label>
                            <input type="text" name="test_name" id="test_name" value="{{ $testName }}" class="form-control">
                        </div>
                        {{-- <div class="form-group">
                            <label for="start_date">Başlangıç Tarihi:</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control" required value="{{ date('Y-m-d\TH:i', strtotime($startDate)) }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date">Bitiş Tarihi:</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control" required value="{{ date('Y-m-d\TH:i', strtotime($endDate)) }}">
                        </div> --}}
                        <div class="form-group">
                            <label for="duration_minutes">Süre (Dakika):</label>
                            <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required value="{{ $durationMinutes }}">
                        </div>

                        <!-- Soruları ve Seçenekleri Ekleme -->
                        <div id="questions_section">
                            @foreach ($questions as $index => $question)
                                <div class="question">
                                    <div class="form-group">
                                        <label for="question_difficulty">Zorluk:</label>
                                        <select name="question_difficulty[]" class="form-control" required>
                                            <option value="1" @if ($question['difficulty'] == 'easy') selected @endif> Kolay</option>
                                            <option value="2" @if ($question['difficulty'] == 'medium') selected @endif> Orta</option>
                                            <option value="3" @if ($question['difficulty'] == 'hard') selected @endif> Zor</option>
                                            <!-- Diğer seçenekleri buraya ekleyebilirsiniz -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="question_points">Puan:</label>
                                        <input type="number" name="question_points[]" class="form-control"
                                            value="{{ $question['points'] }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="question_text">Soru Metni:</label>
                                        <input type="text" name="question_text[]" class="form-control"
                                            value="{{ $question['text'] }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="question_type">Soru Tipi:</label>
                                        <select name="question_type[]" class="form-control" required>
                                            <option value="1" @if ($question['type'] == 1) selected @endif> Çoktan Seçmeli</option>
                                            <option value="2" @if ($question['type'] == 2) selected @endif> Boşluk Doldurma</option>
                                            <!-- Diğer seçenekleri buraya ekleyebilirsiniz -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="answers">Seçenekler:</label>
                                        @foreach ($question['answers'] as $answerIndex => $answer)
                                            <div class="answer">
                                                <input type="text" name="answers[{{ $index }}][text][]"
                                                    class="form-control" value="{{ $answer['text'] }}" required>
                                                <label>
                                                    <input type="radio" name="correct_answer[{{ $index }}]" value="{{ $answerIndex }}" @if ($answer['is_correct']) checked @endif>
                                                    Doğru Cevap
                                                </label>
                                                <button type="button" class="m-2 btn btn-danger"
                                                    onclick="removeAnswer(this)">Seçeneği Kaldır</button>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Yeni Soru Ekle Butonu -->
                        <button type="button" class="btn btn-secondary" onclick="addQuestion()">Yeni Soru Ekle</button>

                        <!-- Gönderme Butonu -->
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var questionIndex = 0;

        function addQuestion() {
            questionIndex++;
            console.log('Question Index:', questionIndex);
            var questionSection = document.getElementById('questions_section');
            var newQuestion = document.createElement('div');
            newQuestion.classList.add('question');
            newQuestion.innerHTML = `
                    <div class="form-group">
                        <label for="question_difficulty">Zorluk:</label>
                        <select name="question_difficulty[]" class="form-control" required>
                            <option value="easy">Kolay</option>
                            <option value="medium">Orta</option>
                            <option value="hard">Zor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question_points">Puan:</label>
                        <input type="number" name="question_points[]" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="question_text">Soru Metni:</label>
                        <input type="text" name="question_text[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="question_type">Soru Tipi:</label>
                        <select name="question_type[]" class="form-control" required>
                            <option value="1">Çoktan Seçmeli</option>
                            <option value="2">Boşluk Doldurma</option>
                            <!-- Diğer seçenekleri buraya ekleyebilirsiniz -->
                        </select>
                    </div>
                    <div class="m-2 form-group">
                        <label for="answers">Seçenekler:</label>
                        <div class="answer-option">

                        </div>
                    </div>
                    <button type="button" class="m-2 btn btn-secondary" onclick="addAnswer(this)">Yeni Seçenek Ekle</button>
                `;
            questionSection.appendChild(newQuestion);
        }

        function addAnswer(button) {
            var answerSection = button.parentNode.querySelector('.answer-option');

            // Soru sayısını kontrol et
            var questionIndex = document.querySelectorAll('.question').length;

            // Seçeneklerin sayısını kontrol et
            var optionsCount = answerSection.querySelectorAll('input[type="text"]').length;

            var newAnswer = document.createElement('div');
            newAnswer.classList.add('answer-option');
            newAnswer.innerHTML = `
                <input type="text" name="answers[${questionIndex - 1}][text][]" class="form-control" required>
                <label>
                    <input type="radio" name="correct_answer[${questionIndex - 1}]" value="${optionsCount}" required> Doğru Cevap
                </label>
                <button type="button" class="m-2 btn btn-danger" onclick="removeAnswer(this)">Seçeneği Kaldır</button>
            `;

            answerSection.appendChild(newAnswer);
        }

        function removeAnswer(button) {
            var answerToRemove = button.parentNode;
            answerToRemove.parentNode.removeChild(answerToRemove);
        }

        // function validateForm() {
        //     var startDate = new Date(document.getElementById('start_date').value);
        //     var endDate = new Date(document.getElementById('end_date').value);
        //     var currentDate = new Date();
        //     var currentDateString = currentDate.toLocaleDateString('tr-TR'); // Güncel tarih ve saat

        //     // Eğer hiç soru eklenmemişse uyarı ver
        //     var questionsCount = document.querySelectorAll('.question').length;
        //     if (questionsCount === 0) {
        //         alert("Lütfen önce bir soru ekleyiniz.");
        //         return false;
        //     }

        //     if (startDate >= endDate) {
        //         alert("Başlangıç tarihi, bitiş tarihinden önce olmalıdır.");
        //         return false;
        //     }
        //     if (startDate < currentDate) {
        //         alert("Başlangıç tarihi, en az güncel tarih kadar olmalıdır. Güncel tarih: " + currentDateString);
        //         return false;
        //     }
        //     return true; // Formun gönderilmesine izin ver
        // }
        function validateForm() {
            var startDate = new Date(document.getElementById('start_date').value);
            var endDate = new Date(document.getElementById('end_date').value);
            var currentDate = new Date();
            var currentDateString = currentDate.toLocaleDateString('tr-TR'); // Güncel tarih ve saat

            // Eğer hiç soru eklenmemişse uyarı ver
            var questionsCount = document.querySelectorAll('.question').length;
            if (questionsCount === 0) {
                alert("Lütfen önce bir soru ekleyiniz.");
                return false;
            }

            // Tarih formatı doğruluğunu kontrol et
            if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
                alert("Lütfen geçerli bir tarih formatı giriniz.");
                return false;
            }

            if (startDate >= endDate) {
                alert("Başlangıç tarihi, bitiş tarihinden önce olmalıdır.");
                return false;
            }
            if (startDate < currentDate) {
                alert("Başlangıç tarihi, en az güncel tarih kadar olmalıdır. Güncel tarih: " + currentDateString);
                return false;
            }

            return true; // Formun gönderilmesine izin ver
        }

    </script>
</x-app-layout>
