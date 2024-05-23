<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Test Düzenleme Sayfası
        </h2>
    </x-slot>

    <div class="py-12 text-white">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <!-- Test Düzenleme Formu -->
                <div class="p-3 text-white card bg-dark">
                    <form method="POST" action="{{ route('quiz.update', ['slug' => $test->slug]) }}" id="formId" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">

                        <div class="m-2 text-white card bg-dark">
                            <div class="card-body">
                                <!-- Test Adı Girişi -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="test_name">Test Adı:</label>
                                            <input type="text" name="test_name" id="test_name" class="form-control" value="{{ $testName }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_minutes">Süre (Dakika):</label>
                                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ $durationMinutes }}"class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="learning_purpose">Öğrenme Amacı:</label>
                                            <select name="learning_purpose" id="learning_purpose" class="form-control">
                                                <option value="egitim" {{ $test->learning_purpose == 'egitim' ? 'selected' : '' }}>Eğitim</option>
                                                <option value="is" {{ $test->learning_purpose == 'is' ? 'selected' : '' }}>İş</option>
                                                <option value="seyehat" {{ $test->learning_purpose == 'seyehat' ? 'selected' : '' }}>Seyahat</option>
                                                <option value="eglence" {{ $test->learning_purpose == 'eglence' ? 'selected' : '' }}>Eğlence</option>
                                                <option value="kultur" {{ $test->learning_purpose == 'kultur' ? 'selected' : '' }}>Kültür</option>
                                                <option value="ailevearkadaslar" {{ $test->learning_purpose == 'ailevearkadaslar' ? 'selected' : '' }}>Aile ve Arkadaşlar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="language_level">Test Seviyesi:</label>
                                            <select name="language_level" id="language_level" class="form-control">
                                                <option value="a1" {{ $test->language_level == 'a1' ? 'selected' : '' }}>A1</option>
                                                <option value="a2" {{ $test->language_level == 'a2' ? 'selected' : '' }}>A2</option>
                                                <option value="b1" {{ $test->language_level == 'b1' ? 'selected' : '' }}>B1</option>
                                                <option value="b2" {{ $test->language_level == 'b2' ? 'selected' : '' }}>B2</option>
                                                <option value="c1" {{ $test->language_level == 'c1' ? 'selected' : '' }}>C1</option>
                                                <option value="c2" {{ $test->language_level == 'c2' ? 'selected' : '' }}>C2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Soruları ve Seçenekleri Ekleme -->
                        <div id="questions_section">
                            <!-- Mevcut Soruları Doldurma -->
                            @foreach($questions as $index => $question)
                                <div class="m-2 text-white card bg-dark">
                                    <div class="card-body">
                                        <span>{{ $index + 1 }}. Soru</span>
                                        <div class="m-2 form-group row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="question_difficulty_{{ $index }}">Zorluk:</label>
                                                    <select name="question_difficulty[]" id="question_difficulty_{{ $index }}" class="form-control" required>
                                                        <option value="easy" {{ $question['difficulty'] == 'easy' ? 'selected' : '' }}>Kolay</option>
                                                        <option value="medium" {{ $question['difficulty'] == 'medium' ? 'selected' : '' }}>Orta</option>
                                                        <option value="hard" {{ $question['difficulty'] == 'hard' ? 'selected' : '' }}>Zor</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="question_points_{{ $index }}">Puan:</label>
                                                        <select name="question_points[]" id="question_points_{{ $index }}" class="form-control">
                                                            <option value="1" {{ $question['points'] == 1 ? 'selected' : '' }}>1</option>
                                                            <option value="2" {{ $question['points'] == 2 ? 'selected' : '' }}>2</option>
                                                            <option value="3" {{ $question['points'] == 3 ? 'selected' : '' }}>3</option>
                                                            <option value="4" {{ $question['points'] == 4 ? 'selected' : '' }}>4</option>
                                                            <option value="5" {{ $question['points'] == 5 ? 'selected' : '' }}>5</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="question_text_{{ $index }}">Soru Metni:</label>
                                                <div class="mb-3 input-group">
                                                    <label class="btn btn-outline-secondary">
                                                        <i class="fa-solid fa-image"></i>
                                                        <input type="file" id="imageInput_{{ $index }}" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">                                                        </label>
                                                    <label class="btn btn-outline-secondary">
                                                        <i class="fa-solid fa-video"></i>
                                                        <input type="file" id="videoInput_{{ $index }}" name="videoInput[]" style="display: none;" accept="video/*" onchange="addVideo(this)">
                                                        </label>
                                                        <input type="text" id="question_text_{{ $index }}" name="question_text[]" class="form-control" value="{{ $question['text'] }}" placeholder="Soru">
                                                </div>
                                            </div>
                                            @if ($question['type'] == 1)
                                                <div class="m-2 form-group row">
                                                    <label for="answers">Seçenekler:</label>
                                                    <div class="answer-option">
                                                        @foreach($question->answers as $answerIndex => $answer)
                                                            <div class="mb-3 row">
                                                                <div class="input-group">
                                                                    <label class="btn btn-outline-secondary">
                                                                        <i class="fa-solid fa-image"></i>
                                                                        <input type="file" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                                                                    </label>
                                                                    <input type="text" name="answers[{{ $index }}][text][]" class="form-control" placeholder="Cevap" value="{{ $answer->text }}">
                                                                    <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="px-5 mt-2 form-check form-check-reverse">
                                                                    <input type="radio" name="correct_answer[{{ $index }}]" class="form-check-input" value="{{ $answerIndex }}" {{ $answer->is_correct ? 'checked' : '' }}>
                                                                    <label class="form-check-label">Doğru Cevap</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <button type="button" class="m-2 btn btn-secondary" onclick="addAnswer(this)">Yeni Seçenek Ekle</button>
                                            @elseif ($question['type'] == 2)
                                                <div class="m-2 form-group row">
                                                    <label for="answers">Eşleştirme Çiftleri:</label>
                                                    <div class="answer-options">
                                                        @foreach ($question['matching_pairs'] as $pairIndex => $pair)
                                                            <div class="mb-3 row justify-content-center">
                                                                <div class="col-md">
                                                                    <div class="mb-3 row">
                                                                        <div class="input-group">
                                                                            <label class="btn btn-outline-secondary">
                                                                                <i class="fa-solid fa-image"></i>
                                                                                <input type="file" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                                                                            </label>
                                                                            <input type="text" name="answers[{{ $index }}][pair1][]" class="form-control" placeholder="Sol Seçenek" value="{{ $pair->pair1 }}">
                                                                            <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                                                                <i class="fa-solid fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md">
                                                                    <div class="mb-3 row">
                                                                        <div class="input-group">
                                                                            <label class="btn btn-outline-secondary">
                                                                                <i class="fa-solid fa-image"></i>
                                                                                <input type="file" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                                                                            </label>
                                                                            <input type="text" name="answers[{{ $index }}][pair2][]" class="form-control" placeholder="Sağ Seçenek" value="{{ $pair->pair2 }}">
                                                                            <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                                                                <i class="fa-solid fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <button type="button" class="m-2 btn btn-secondary" onclick="addAnswer(this)">Yeni Eşleştirme Çifti Ekle</button>
                                            @elseif($question->type == 3)
                                                <div class="m-2 form-group row">
                                                    <label for="answers">Boşluk Doldurma Cümleleri:</label>
                                                    <div class="answer-options">
                                                        @foreach($question->fill_in_the_blanks as $blankIndex => $blank)
                                                            <div class="mb-3 row">
                                                                <div class="input-group">
                                                                    <label class="btn btn-outline-secondary">
                                                                        <i class="fa-solid fa-image"></i>
                                                                        <input type="file" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                                                                    </label>
                                                                    <input type="text" name="answers[{{ $index }}][text][]" class="form-control" placeholder="Boşluk Doldurma Cümlesi" value="{{ $blank->text }}">
                                                                    <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="px-5 mt-2 form-check form-check-reverse">
                                                                    <input type="text" name="correct_answer[{{ $index }}][blank][]" class="form-control" placeholder="Doğru Cevap" value="{{ $blank->correct_answer }}">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <button type="button" class="m-2 btn btn-secondary" onclick="addAnswer(this)">Yeni Boşluk Doldurma Cümlesi Ekle</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="m-3 btn btn-primary" onclick="addQuestion()">Yeni Soru Ekle</button>
                        <button type="submit" class="m-3 btn btn-success">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function addQuestion() {
            const questionSection = document.getElementById('questions_section');
            const questionIndex = questionSection.childElementCount + 1;

            const questionCard = document.createElement('div');
            questionCard.className = 'm-2 text-white card bg-dark';
            questionCard.innerHTML = `
                <div class="card-body">
                    <span>${questionIndex}. Soru</span>
                    <div class="m-2 form-group row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="question_difficulty">Zorluk:</label>
                                <select name="question_difficulty[]" class="form-control" required>
                                    <option value="easy">Kolay</option>
                                    <option value="medium">Orta</option>
                                    <option value="hard">Zor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="question_points">Puan:</label>
                                <select name="question_points[]" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="question_text">Soru Metni:</label>
                            <div class="mb-3 input-group">
                                <label class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-image"></i>
                                    <input type="file" id="imageInput" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                                </label>
                                <label class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-video"></i>
                                    <input type="file" id="videoInput" name="videoInput[]" style="display: none;" accept="video/*" onchange="addVideo(this)">
                                </label>
                                <input type="text" id="question_text" name="question_text[]" class="form-control" placeholder="Soru">
                            </div>
                        </div>
                        <div class="m-2 form-group row">
                            <label for="answers">Seçenekler:</label>
                            <div class="answer-option">
                                <div class="mb-3 row">
                                    <div class="input-group">
                                        <label class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-image"></i>
                                            <input type="file" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                                        </label>
                                        <input type="text" name="answers[${questionIndex - 1}][text][]" class="form-control" placeholder="Cevap">
                                        <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="px-5 mt-2 form-check form-check-reverse">
                                        <input type="radio" name="correct_answer[${questionIndex - 1}]" class="form-check-input" value="0">
                                        <label class="form-check-label">Doğru Cevap</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            questionSection.appendChild(questionCard);
        }

        function addAnswer(button) {
            const answerOption = button.previousElementSibling.querySelector('.answer-option');
            const answerIndex = answerOption.childElementCount;

            const answerDiv = document.createElement('div');
            answerDiv.className = 'mb-3 row';
            answerDiv.innerHTML = `
                <div class="input-group">
                    <label class="btn btn-outline-secondary">
                        <i class="fa-solid fa-image"></i>
                        <input type="file" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                    </label>
                    <input type="text" name="answers[${answerOption.dataset.questionIndex}][text][]" class="form-control" placeholder="Cevap">
                    <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div class="px-5 mt-2 form-check form-check-reverse">
                    <input type="radio" name="correct_answer[${answerOption.dataset.questionIndex}]" class="form-check-input" value="${answerIndex}">
                    <label class="form-check-label">Doğru Cevap</label>
                </div>
            `;

            answerOption.appendChild(answerDiv);
        }

        function removeAnswer(button) {
            button.closest('.row').remove();
        }
    </script>
</x-app-layout>
