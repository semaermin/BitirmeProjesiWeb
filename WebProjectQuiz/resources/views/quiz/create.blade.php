<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Test Oluşturma Sayfası
        </h2>
    </x-slot>

    <div class="py-12 text-white">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-xl bg-dark dark:bg-gray-800 sm:rounded-lg">
                <!-- Test Oluşturma Formu -->
                <div class="p-3 text-white card bg-dark">
                    <form method="POST" action="{{ route('quiz.add') }}" id="formId" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="admin_id" value="{{ auth()->user()->id }}">

                        <div class="m-2 text-white card bg-dark">
                            <div class="card-body">
                                <!-- Test Adı Girişi -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="test_name">Test Adı:</label>
                                            <input type="text" name="test_name" id="test_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="duration_minutes">Süre (Dakika):</label>
                                            <input type="number" name="duration_minutes" id="duration_minutes" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="learning_purpose">Öğrenme Amacı:</label>
                                            <select name="learning_purpose" id="learning_purpose" class="form-control">
                                                <option value="egitim">Eğitim</option>
                                                <option value="is">İş</option>
                                                <option value="seyehat">Seyahat</option>
                                                <option value="eglence">Eğlence</option>
                                                <option value="kultur">Kültür</option>
                                                <option value="ailevearkadaslar">Aile ve Arkadaşlar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="language_level">Test Seviyesi:</label>
                                            <select name="language_level" id="language_level" class="form-control">
                                                <option value="a1">A1</option>
                                                <option value="a2">A2</option>
                                                <option value="b1">B1</option>
                                                <option value="b2">B2</option>
                                                <option value="c1">C1</option>
                                                <option value="c2">C2</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Soruları ve Seçenekleri Ekleme -->
                        <div id="questions_section">
                            <!-- Buraya otomatik olarak soru alanları eklenecek -->
                        </div>
                        <!-- Yeni Soru Ekle Butonu -->
                        <button type="button" class="m-2 btn btn-secondary" data-bs-toggle="modal" data-bs-target="#exampleModal">Yeni Soru Ekle</button>

                        <!-- Gönderme Butonu -->
                        <button type="submit" class="m-2 btn btn-primary" onclick="return validateForm()">Testi
                            Oluştur</button>
                    </form>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>


<!-- Modal -->
<div class="text-white modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Soru Ekle</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item" onclick="addQuestion(1)">Seçenekli Soru</li>
                    <li class="list-group-item" onclick="addQuestion(2)">Eşleştirmeli Soru</li>
                    <!-- Diğer soru tipleri buraya eklenebilir -->
                </ul>
            </div>
            <div class="hidden modal-footer">
                <button type="button" class="btn btn-secondary" id="closeModal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var questionIndex = 0;

    function addQuestion(soruTipi) {
        var formIcerik = document.getElementById("questions_section");

        // Soru sayısını kontrol et ve güncelleyin
        questionIndex++;
        console.log('Question Index:', questionIndex);

        // Kart içeriğini oluştur
        var kart = document.createElement('div');
        kart.classList.add('m-2', 'text-white', 'card', 'bg-dark');

        var cardBody = document.createElement('div');
        cardBody.classList.add('card-body');

        var questionNumber = document.createElement('span');
        questionNumber.textContent = questionIndex + ". Soru";
        cardBody.appendChild(questionNumber);

        var kartIcerik = document.createElement('div');
        kartIcerik.classList.add('m-2', 'form-group', 'row');

        if (soruTipi == 1) {
            kartIcerik.innerHTML = `
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

                    </div>
                </div>
                <button type="button" class="m-2 btn btn-secondary" onclick="addAnswer(this)">Yeni Seçenek Ekle</button>
            `;
        } else if (soruTipi == 2) {
            kartIcerik.innerHTML = `
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
                        <input type="number" name="question_points[]" class="form-control">
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
                    <div class="answer-options">

                    </div>
                </div>
                <button type="button" class="m-2 btn btn-secondary" onclick="addMatchingPair(this)">Yeni Seçenek Ekle</button>
            `;
        }
        cardBody.appendChild(kartIcerik);
        kart.appendChild(cardBody);
        formIcerik.appendChild(kart);

        // Eklenen soru tipini form alanına ekleyin
        var questionTypeInput = document.createElement('input');
        questionTypeInput.setAttribute('type', 'hidden');
        questionTypeInput.setAttribute('name', 'question_type[]');
        questionTypeInput.value = soruTipi;
        kartIcerik.appendChild(questionTypeInput);

        $("#closeModal").click(); // Modalı kapat

    }

    function addAnswer(button) {
        var answerSection = button.parentNode.querySelector('.answer-option');

        // Seçeneklerin sayısını kontrol et
        var optionsCount = answerSection.querySelectorAll('.mb-3.row input[type="text"]').length;
        console.log("Seçenek Sayısı:", optionsCount);

        var newAnswer = document.createElement('div');
        newAnswer.classList.add('answer-option');
        newAnswer.innerHTML = `
            <!-- Yeni Cevap için form alanları -->
            <div class="mb-3 row">
                <div class="input-group">
                    <label class="btn btn-outline-secondary">
                        <i class="fa-solid fa-image"></i>
                        <input type="file" id="imageInput" name="imageInput[]" style="display: none;" accept="image/*" onchange="addImage(this)">
                    </label>
                    <input type="text" name="answers[${questionIndex - 1}][text][]" class="form-control" placeholder="Cevap" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
                <div class="px-5 mt-2 form-check form-check-reverse">
                    <input type="radio" name="correct_answer[${questionIndex - 1}]" class="form-check-input" value="${optionsCount}">
                    <label class="form-check-label">Doğru Cevap</label>
                </div>
            </div>
        `;

        answerSection.appendChild(newAnswer);
    }

    // function removeAnswer(button) {
    //     var answerSection = button.closest('.answer-option');
    //     var answerToRemove = button.closest('.mb-3.row');
    //     var allAnswers = answerSection.querySelectorAll('.mb-3.row');

    //     // Tüm cevapların sayısını kontrol et
    //     var optionsCount = answerSection.querySelectorAll('.mb-3.row input[type="text"]').length;
    //     console.log("Seçenek Sayısı:", optionsCount);

    //     // En az bir cevap kalmalı
    //     if (optionsCount > 1) {
    //         answerToRemove.parentNode.removeChild(answerToRemove);

    //         // Silinen cevaba bağlı olan doğru cevap checkboxunu da sil
    //         var correctAnswerCheckbox = answerToRemove.querySelector('.form-check-input');
    //         if (correctAnswerCheckbox) {
    //             correctAnswerCheckbox.parentNode.parentNode.removeChild(correctAnswerCheckbox.parentNode);
    //         }
    //     } else {
    //         alert("En az bir cevap kalmalı!");
    //     }
    // }

    function removeAnswer(button) {
        var answerSection = button.closest('.answer-option');
        var answerToRemove = button.closest('.mb-3.row');
        var allAnswers = answerSection.querySelectorAll('.mb-3.row');

        // Tüm cevapların sayısını kontrol et
        var optionsCount = answerSection.querySelectorAll('.mb-3.row input[type="text"]').length;
        console.log("Seçenek Sayısı:", optionsCount);

        // En az bir cevap kalmalı
        if (optionsCount > 1) {
            console.log("Seçenek Sayısı:", optionsCount);
            answerToRemove.parentNode.removeChild(answerToRemove);

            // Silinen cevaba bağlı olan doğru cevap checkboxunu da sil
            var correctAnswerCheckbox = answerToRemove.querySelector('.form-check-input');
            if (correctAnswerCheckbox) {
                correctAnswerCheckbox.parentNode.parentNode.removeChild(correctAnswerCheckbox.parentNode);
            }
        } else {
            alert("En az bir cevap kalmalı!");
        }
    }

    // function addMatchingPair(button) {
    //     var answerSection = button.parentNode.querySelector('.answer-options');
    //     var newMatchingPair = document.createElement('div');
    //     newMatchingPair.classList.add('matching-pair');

    //     newMatchingPair.innerHTML = `
    //         <!-- Eşleştirme için form alanları -->
    //         <div class="mb-3 row justify-content-center">
    //             <div class="col-md">
    //                 <div class="mb-3 row">
    //                     <div class="input-group">
    //                         <label class="btn btn-outline-secondary">
    //                             <i class="fa-solid fa-image"></i>
    //                             <input type="file" id="fileInput" name="fileInput" style="display: none;" accept="image/*" onchange="addImage(this)">
    //                         </label>
    //                         <input type="text" name="matching-pair[${questionIndex - 1}][text][]" class="form-control" placeholder="Cevap" required>
    //                         <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
    //                             <i class="fa-solid fa-trash"></i>
    //                         </button>
    //                     </div>
    //                 </div>
    //             </div>
    //             <div class="col-md d-flex justify-content-center align-items-center"><i class="fa-solid fa-arrows-left-right"></i></div>
    //             <div class="col-md">
    //                 <div class="mb-3 row">
    //                     <div class="input-group">
    //                         <button class="btn btn-outline-secondary" type="button" onclick="addImage(this)">
    //                             <i class="fa-solid fa-image"></i>
    //                         </button>
    //                         <input type="text" name="matching-pair[${questionIndex - 1}][text][]" class="form-control" placeholder="Cevap" required>
    //                         <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
    //                             <i class="fa-solid fa-trash"></i>
    //                         </button>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>

    //     `;

    //     answerSection.appendChild(newMatchingPair);
    // }

    var matchingPairs = [];

    function addMatchingPair(button) {
        var answerSection = button.parentNode.querySelector('.answer-options');
        var newMatchingPair = document.createElement('div');
        newMatchingPair.classList.add('matching-pair');

        var questionIndex = matchingPairs.length; // Soru dizisinin uzunluğu, mevcut soru sayısını temsil eder

        newMatchingPair.innerHTML = `
            <!-- Eşleştirme için form alanları -->
            <div class="mb-3 row justify-content-center">
                <div class="col-md">
                    <div class="mb-3 row">
                        <div class="input-group">
                            <label class="btn btn-outline-secondary">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" name="matching_pairs[${questionIndex}][left]" style="display: none;" accept="image/*" onchange="addImage(this)">
                            </label>
                            <input type="text" name="matching_pairs[${questionIndex}][left]" class="form-control" placeholder="Sol Eş" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md d-flex justify-content-center align-items-center"><i class="fa-solid fa-arrows-left-right"></i></div>
                <div class="col-md">
                    <div class="mb-3 row">
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" onclick="addImage(this)">
                                <i class="fa-solid fa-image"></i>
                            </button>
                            <input type="text" name="matching_pairs[${questionIndex}][right]" class="form-control" placeholder="Sağ Eş" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        answerSection.appendChild(newMatchingPair);

        // Yeni eşleştirme çiftini eşleştirme dizisine ekleyin
        matchingPairs.push([]);
    }


    function removeMatchingPair(button) {
        var matchingPairToRemove = button.closest('.matching-pair');
        matchingPairToRemove.parentNode.removeChild(matchingPairToRemove);
    }
</script>