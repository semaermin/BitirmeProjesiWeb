
var questionIndex = 0;
var optionsCount=0;

function addAnswer(button) {
    var answerSection = button.parentNode.querySelector('.answer-option');

    // Seçeneklerin sayısını kontrol et
    optionsCount = answerSection.querySelectorAll('.mb-3.row').length;
    console.log('Answers Index:', optionsCount);
    questionIndex = answerSection.getAttribute('data-answer-index');

    var newAnswer = document.createElement('div');
    newAnswer.classList.add('answer-option');
    var currentAnswerIndex = optionsCount; // Şu anki cevap indeksi
    newAnswer.innerHTML = `
        <!-- Yeni Cevap için form alanları -->
        <div class="mb-3 row">
            <div class="input-group">
                <button class="btn btn-outline-secondary" type="button">
                    <input type="radio" name="correct_answer[${questionIndex - 1}]" class="form-check-input" style="margin:unset;" value="${currentAnswerIndex}">
                </button>
                <input type="text" name="answers[${questionIndex - 1}][text][]" class="form-control" placeholder="Cevap" required>
                <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    answerSection.appendChild(newAnswer);
}

function removeAnswer(button) {
    var answerDiv = button.parentNode.parentNode; // Cevabın bulunduğu div
    var answerSection = answerDiv.closest('.answer-option'); // Cevabın bulunduğu ana bölümü seç

    var questionIndex = answerSection.getAttribute('data-answer-index'); // Soru indeksini al
    if (questionIndex === null) {
        questionIndex = 0; // Varsayılan olarak 0 atanabilir veya başka bir değer
    } else {
        questionIndex = parseInt(questionIndex); // Doğru sayısal değeri almak için parseInt kullanılır
    }

    console.log('Remove - Question Index:', questionIndex);

    // Seçilen cevabı DOM'dan kaldır
    answerDiv.remove();

    // Kalan cevap sayısını güncelleyin
    var optionsContainer = answerSection; // .answer-option olarak değiştirildi
    var options = optionsContainer.querySelectorAll('.mb-3.row');
    console.log('Remaining Options Count:', options.length);

    // Cevaplar indeksini güncelleyin
    options.forEach((option, index) => {
        var deleteButton = option.querySelector('button[type="button"]');
        if (deleteButton) {
            deleteButton.setAttribute('onclick', `removeAnswer(this)`);
        }
    });
}





function validateForm() {
    if (questionIndex < 1) {
        alert('En az bir soru eklemelisiniz.');
        return false;
    }

    for (let i = 1; i <= questionIndex; i++) {
        const answerCount = document.querySelectorAll(`.answer-option[data-answer-index="${i}"] input[type="text"]`).length;

        if (answerCount < 2) {
            alert(`${i}.Soru için en az iki cevap eklemelisiniz.`);
            return false;
        }
    }

    return true;
}


function addQuestion(soruTipi) {
    var formIcerik = document.getElementById("questions_section");

    // Soru sayısını kontrol et ve güncelleyin
    questionIndex++;
    console.log('Question Index:', questionIndex);

    // Kart içeriğini oluştur
    var kart = document.createElement('div');
    kart.classList.add('m-2', 'text-white', 'card', 'bg-dark');
    kart.setAttribute('data-question-index', questionIndex); // Her karta soru indeksini ekleyin

    var cardBody = document.createElement('div');
    cardBody.classList.add('card-body');

    var questionNumber = document.createElement('span');
    questionNumber.textContent = questionIndex + ". Soru";
    cardBody.appendChild(questionNumber);

    var kartIcerik = document.createElement('div');
    kartIcerik.classList.add('m-2', 'form-group', 'row');

    if (soruTipi == 1) {
        kartIcerik.innerHTML = `
            <div class="row">
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
                    <button class="btn btn-outline-secondary" type="button" onclick="removeQuestion(this)">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="form-group row">
                <label for="answers">Seçenekler:</label>
                <div class="answer-option" data-answer-index="${questionIndex}">

                </div>
            </div>
            <button type="button" class="m-2 mx-auto btn btn-secondary d-block" style="width: 50% !important;" onclick="addAnswer(this)">Yeni Seçenek Ekle</button>
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



// var matchingPairs = [];

// function addMatchingPair(button) {
//     var answerSection = button.parentNode.querySelector('.answer-options');
//     var newMatchingPair = document.createElement('div');
//     newMatchingPair.classList.add('matching-pair');

//     var questionIndex = matchingPairs.length; // Soru dizisinin uzunluğu, mevcut soru sayısını temsil eder

//     newMatchingPair.innerHTML = `
//         <!-- Eşleştirme için form alanları -->
//         <div class="mb-3 row justify-content-center">
//             <div class="col-md">
//                 <div class="mb-3 row">
//                     <div class="input-group">
//                         <button class="btn btn-outline-secondary" type="button" onclick="addImage(this)">
//                             <i class="fa-solid fa-image"></i>
//                         </button>
//                         <input type="text" name="matching_pairs[${questionIndex}][left]" class="form-control" placeholder="Sol Eş" required>
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
//                         <input type="text" name="matching_pairs[${questionIndex}][right]" class="form-control" placeholder="Sağ Eş" required>
//                         <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
//                             <i class="fa-solid fa-trash"></i>
//                         </button>
//                     </div>
//                 </div>
//             </div>
//         </div>
//     `;

//     answerSection.appendChild(newMatchingPair);

//     // Yeni eşleştirme çiftini eşleştirme dizisine ekleyin
//     matchingPairs.push([]);
// }


function removeMatchingPair(button) {
    var matchingPairToRemove = button.closest('.matching-pair');
    matchingPairToRemove.parentNode.removeChild(matchingPairToRemove);
}

function removeQuestion(button) {
    // Soru kartını alın ve DOM'dan kaldırın
    var questionCard = button.closest('.card');
    questionCard.remove();

    // Kalan soruların indekslerini güncelleyin
    var remainingQuestions = document.querySelectorAll('.card[data-question-index]');
    remainingQuestions.forEach((card, index) => {
        card.setAttribute('data-question-index', index + 1);
        card.querySelector('span').textContent = (index + 1) + ". Soru";

        // Soru numarasını güncellemek için card içindeki span öğesini bulun
        var questionNumberSpan = card.querySelector('span');
        if (questionNumberSpan) {
            questionNumberSpan.textContent = (index + 1) + ". Soru";
        }

        // Seçeneklerin adlarını güncelleyin
        var inputs = card.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
        });
    });

    // Soru sayısını güncelleyin
    questionIndex = remainingQuestions.length;
}

