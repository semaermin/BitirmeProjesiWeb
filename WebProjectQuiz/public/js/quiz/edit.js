function addImage(input, index) {
    var file = input.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var image = new Image();
            image.src = e.target.result;
            image.onload = function () {
                // Resmi önizleme olarak ekleyin
                $('#imagePreview_' + index).attr('src', e.target.result);

                // Gizli inputa dosya yolu ekle
                $('input[name="existing_image_' + index + '"]').val('');
            };
        };
        reader.readAsDataURL(file);
    }
}

function addVideo(input, index) {
    var file = input.files[0];
    if (file) {
        // Video önizlemesi için src değiştirme
        $('#videoPreview_' + index).attr('src', URL.createObjectURL(file));

        // Gizli inputa dosya yolu ekle
        $('input[name="existing_video_' + index + '"]').val('');
    }
}


let questionIndex = document.querySelectorAll('#questions_section .card').length;

function removeQuestion(button) {
    var questionCard = button.closest('.card');
    questionCard.remove();

    var remainingQuestions = document.querySelectorAll('#questions_section .card');
    remainingQuestions.forEach((card, index) => {
        card.setAttribute('data-question-index', index + 1);
        card.querySelector('span').textContent = (index + 1) + ". Soru";

        var inputs = card.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
        });
    });

    questionIndex = remainingQuestions.length;
}

function removeAnswer(button) {
    var answerSection = button.closest('.answer-option');
    var answerToRemove = button.closest('.mb-3.row');
    var allAnswers = answerSection.querySelectorAll('.mb-3.row');

    var optionsCount = answerSection.querySelectorAll('.mb-3.row input[type="text"]').length;

    if (optionsCount > 1) {
        answerToRemove.parentNode.removeChild(answerToRemove);
    } else {
        alert("En az bir cevap kalmalı!");
    }
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add_question_button').addEventListener('click', function () {
        const index = document.querySelectorAll('#questions_section .card').length;
        const questionTemplate = `
        <div class="m-2 text-white card bg-dark" data-question-index="${index + 1}">
            <div class="card-body">
                <span>${index + 1}. Soru</span>
                <input type="hidden" name="question_type[]" value="1">
                <div class="form-group">
                    <p>Soru için medya dosyası yok</p>
                </div>
                <div class="m-2 form-group row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="question_difficulty_${index}">Zorluk:</label>
                            <select name="question_difficulty[]" id="question_difficulty_${index}" class="form-control" required>
                                <option value="easy">Kolay</option>
                                <option value="medium">Orta</option>
                                <option value="hard">Zor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="question_points_${index}">Puan:</label>
                            <select name="question_points[]" id="question_points_${index}" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="question_text_${index}">Soru Metni:</label>
                        <div class="mb-3 input-group">
                            <label class="btn btn-outline-secondary">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" id="imageInput_${index}" name="imageInput[${index}]" style="display: none;" accept="image/*" onchange="addImage(this)">
                            </label>
                            <label class="btn btn-outline-secondary">
                                <i class="fa-solid fa-video"></i>
                                <input type="file" id="videoInput_${index}" name="videoInput[${index}]" style="display: none;" accept="video/*" onchange="addVideo(this)">
                            </label>
                            <input type="text" id="question_text_${index}" name="question_text[]" class="form-control" placeholder="Soru" required>
                        </div>
                    </div>
                    <div class="m-2 form-group row">
                        <label for="answers">Seçenekler:</label>
                        <div class="answer-option">
                            <div class="mb-3 row">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <input type="radio" name="correct_answer[${index}]" class="form-check-input" style="margin:unset;" value="0">
                                    </button>
                                    <input type="text" name="answers[${index}][text][]" class="form-control" placeholder="Cevap" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="m-2 mx-auto btn btn-secondary d-block" style="width: 50% !important;" onclick="addAnswer(this)">Yeni Seçenek Ekle</button>
                </div>
            </div>
        </div>`;
        document.getElementById('questions_section').insertAdjacentHTML('beforeend', questionTemplate);
    });
});

function addAnswer(button) {
    var questionCard = button.closest('.card');
    var answerSection = questionCard.querySelector('.answer-option');
    var optionsCount = answerSection.querySelectorAll('.mb-3.row input[type="text"]').length;
    var questionIndex = parseInt(questionCard.getAttribute('data-question-index'), 10) - 1;

    const answerTemplate = `
    <div class="mb-3 row">
        <div class="input-group">
            <button class="btn btn-outline-secondary" type="button">
                <input type="radio" name="correct_answer[${questionIndex}]" class="form-check-input" style="margin:unset;" value="${optionsCount}">
            </button>
            <input type="text" name="answers[${questionIndex}][text][]" class="form-control" placeholder="Cevap" required>
            <button class="btn btn-outline-secondary" type="button" onclick="removeAnswer(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    </div>`;
    answerSection.insertAdjacentHTML('beforeend', answerTemplate);
}
