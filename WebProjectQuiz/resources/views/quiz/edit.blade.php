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
                                            <input type="text" name="test_name" id="test_name" class="form-control" value="{{ $testName }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_minutes">Süre (Dakika):</label>
                                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ $durationMinutes }}" class="form-control" required>
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
                            <div class="m-2 text-white card bg-dark" data-question-index="{{ $index + 1 }}">
                                <div class="card-body">
                                    <span>{{ $index + 1 }}. Soru</span>
                                        <input type="hidden" name="question_type[]" value="{{ $question['type'] }}">

                                        <div class="form-group">
                                            @if ($question['media_path'])
                                                @if (pathinfo($question['media_path'], PATHINFO_EXTENSION) == 'mp4')
                                                    <video width="320" height="240" controls>
                                                        <source src="{{ asset('storage/' . $question['media_path']) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                    <input type="hidden" name="existing_video[{{ $index }}]" value="{{ $question['media_path'] }}">
                                                @else
                                                    <img src="{{ asset('storage/' . $question['media_path']) }}" alt="Soru Resmi" width="250">
                                                    <input type="hidden" name="existing_image[{{ $index }}]" value="{{ $question['media_path'] }}">
                                                @endif
                                            @else
                                                <p>Soru için medya dosyası yok</p>
                                            @endif
                                        </div>
                                    <div class="mb-3 form-group row">
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
                                        <div class="mb-2 form-group row">
                                            <label for="question_text_{{ $index }}">Soru Metni:</label>
                                            <div class="mb-3 input-group">
                                                <label class="btn btn-outline-secondary">
                                                    <i class="fa-solid fa-image"></i>
                                                    <input type="file" id="imageInput_{{ $index }}" name="imageInput[{{ $index }}]" style="display: none;" accept="image/*" onchange="addImage(this, {{ $index }})">
                                                </label>
                                                <label class="btn btn-outline-secondary">
                                                    <i class="fa-solid fa-video"></i>
                                                    <input type="file" id="videoInput_{{ $index }}" name="videoInput[{{ $index }}]" style="display: none;" accept="video/*" onchange="addVideo(this, {{ $index }})">
                                                </label>
                                                <input type="text" id="question_text_{{ $index }}" name="question_text[]" class="form-control" value="{{ $question['text'] }}" placeholder="Soru" required>
                                                <button class="btn btn-outline-secondary" type="button" onclick="removeQuestion(this)">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                <input type="hidden" name="existing_image_{{ $index }}" value="{{ $question['media_path'] ?? '' }}">
                                                <input type="hidden" name="existing_video_{{ $index }}" value="{{ $question['media_path'] ?? '' }}">
                                            </div>
                                        </div>
                                        @if ($question['type'] == 1)
                                        <div class="form-group row">
                                            <label for="answers">Seçenekler:</label>
                                            <div class="answer-option">
                                                @foreach ($question['answers'] as $answerIndex => $answer)
                                                <div class="mb-3 row">
                                                    <input type="hidden" name="answer_id[{{ $index }}][]" value="{{ $answer['id'] ?? '' }}">
                                                    <div class="input-group">
                                                        <button class="btn btn-outline-secondary" type="button">
                                                            <input type="radio" id="correct_answer_{{ $index }}_{{ $answerIndex }}" name="correct_answer[{{ $index }}]" value="{{ $answerIndex }}" {{ $answer['is_correct'] ? 'checked' : '' }}>
                                                        </button>
                                                        <input type="text" id="{{ $index }}_{{ $answerIndex }}" name="answers[{{ $index }}][text][]" class="form-control" placeholder="Cevap" value="{{ $answer['text'] }}" required>
                                                        <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <input type="radio" name="correct_answer[{{ $index }}]" class="form-check-input" style="margin:unset;" value="0" {{ $question['is_correct'] ? 'checked' : '' }}>
                                            </button>
                                            <input type="text" id="correct_text_answer_{{ $index }}" name="correct_text_answer[{{ $index }}]" class="form-control" value="{{ $question['correct_text_answer'] }}" required>
                                        </div>
                                    @endif
                                    <button type="button" class="m-2 mx-auto btn btn-secondary d-block" style="width: 50% !important;" onclick="addAnswer(this)">Yeni Seçenek Ekle</button>

                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="m-2 btn btn-secondary" id="add_question_button">Yeni Soru Ekle</button>
                        <button type="submit" class="m-2 btn btn-danger">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="{{ asset('js/quiz/edit.js') }}"></script>
    @endsection

</x-app-layout>
