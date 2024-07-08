import Navbar from '../components/Navbar';
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import useUpdateUserPoints from '../utils/UseUpdateUserPoints.js';
import '../assets/styles/exercises-page.scss';

function ExercisesPage() {
  const [test, setTest] = useState({});
  const [tests, setTests] = useState([]);
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedAnswers, setSelectedAnswers] = useState({});
  const [shuffledAnswers, setShuffledAnswers] = useState({});
  const { slug } = useParams();
  const { theme, user } = useTheme();
  const updateUserPoints = useUpdateUserPoints();

  useEffect(() => {
    if (slug) {
      fetchTestDetails(slug);
    }
  }, [slug]);

  useEffect(() => {
    fetchTestList();
  }, []);

  async function fetchTestDetails(slug) {
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_API_URL}/test-list/${slug}`
      );
      if (response.data && response.data.test) {
        setTest(response.data.test);
        setCurrentQuestionIndex(0); // Yeni bir test yüklendiğinde mevcut soru indeksini sıfırla
        setShuffledAnswers({}); // Yeni test geldiğinde shuffledAnswers'ı sıfırla
      } else {
        console.error('Test detayları getirilemedi:', response.statusText);
      }
    } catch (error) {
      console.error('Test detayları getirilemedi:', error.message);
    }
  }

  async function fetchTestList() {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.error('Authorization token not found');
        return;
      }

      const response = await axios.get(
        `${import.meta.env.VITE_API_URL}/test-list`,
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        }
      );
      if (response.data && response.data.tests) {
        setTests(response.data.tests);
      } else {
        console.error('Test listesi getirilemedi:', response.statusText);
      }
    } catch (error) {
      console.error('Test listesi getirilemedi:', error.message);
    }
  }

  // Dizi elemanlarını karıştırmak için shuffle fonksiyonu
  function shuffleArray(array) {
    let shuffledArray = [...array];
    for (let i = shuffledArray.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [shuffledArray[i], shuffledArray[j]] = [
        shuffledArray[j],
        shuffledArray[i],
      ];
    }
    return shuffledArray;
  }

  // Test değiştiğinde şıkları karıştır ve shuffledAnswers state'ine ata
  useEffect(() => {
    if (test.questions) {
      const newShuffledAnswers = {};
      test.questions.forEach((question, index) => {
        newShuffledAnswers[index] = shuffleArray(question.answers);
      });
      setShuffledAnswers(newShuffledAnswers);
    }
  }, [test]);

  // Testleri language_level'e göre kategorize et
  const categorizedTests = ['a1', 'a2', 'b1'].reduce((acc, level) => {
    const filteredTests = tests.filter((test) => test.language_level === level);
    if (filteredTests.length > 0) {
      acc[level] = filteredTests;
    }
    return acc;
  }, {});

  const handleQuestionNavigation = (direction) => {
    if (direction === 'next') {
      if (currentQuestionIndex < test.questions.length - 1) {
        setCurrentQuestionIndex(currentQuestionIndex + 1);
      } else {
        alert('Test tamamlandı!');
      }
    } else if (direction === 'prev') {
      if (currentQuestionIndex > 0) {
        setCurrentQuestionIndex(currentQuestionIndex - 1);
      }
    }
  };

  const handleAnswerSelect = (questionIndex, answerId) => {
    setSelectedAnswers({
      ...selectedAnswers,
      [questionIndex]: answerId,
    });
  };

  const handleSubmit = async () => {
    const unansweredQuestions = test.questions.filter(
      (_, index) => !(index in selectedAnswers)
    );

    if (unansweredQuestions.length > 0) {
      alert('Lütfen tüm soruları yanıtlayın.');
      return;
    }

    try {
      const answers = Object.keys(selectedAnswers).map((questionIndex) => ({
        questionId: test.questions[questionIndex].id,
        answerId: selectedAnswers[questionIndex],
      }));

      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/api/check-answers`,
        {
          userId: user.id,
          testId: test.id,
          answers: answers,
        }
      );

      response.data.totalPoints > 0
        ? alert(`Tebrikler ${response.data.totalPoints} puan kazandın 👏🏻😄`)
        : alert(
            'Malesef tüm sorulara yanlış cevap verdin ve puan kazanamadın!'
          );

      updateUserPoints(response.data.userPoint);
    } catch (error) {
      console.error('Yanıtlar gönderilemedi:', error.message);
    }
  };

  const currentQuestion = test.questions
    ? test.questions[currentQuestionIndex]
    : null;

  return (
    <div>
      <Navbar item="exercises" />
      <div className={theme}>
        {slug && test.name ? (
          <div className="question-container">
            <div className="question">
              <h2 className="question-number">
                Soru {currentQuestionIndex + 1}
              </h2>
              {currentQuestion?.media_path &&
                currentQuestion?.is_video === 0 && (
                  <img
                    src={`${import.meta.env.VITE_API_URL}/storage/${
                      currentQuestion.media_path
                    }`}
                    alt="question-photo"
                    className="question-photo"
                  />
                )}

              {currentQuestion?.is_video === 1 && (
                <div className="question-video-container">
                  <video autoPlay controls className="question-video">
                    <source
                      src={`${import.meta.env.VITE_API_URL}/storage/${
                        currentQuestion.media_path
                      }`}
                      type="video/mp4"
                    />
                  </video>
                </div>
              )}

              {currentQuestion ? (
                <>
                  <h4 className="question-text">{currentQuestion.text}</h4>
                  <ul className="answer-group">
                    {shuffledAnswers[currentQuestionIndex] &&
                      shuffledAnswers[currentQuestionIndex].length > 0 &&
                      shuffledAnswers[currentQuestionIndex].map(
                        (answer, answerIndex) => (
                          <li className="answer-group-item" key={answerIndex}>
                            <input
                              type="radio"
                              name={`question-${currentQuestionIndex}`}
                              value={answer.id}
                              id={answer.id}
                              checked={
                                selectedAnswers[currentQuestionIndex] ===
                                answer.id
                              }
                              onChange={() =>
                                handleAnswerSelect(
                                  currentQuestionIndex,
                                  answer.id
                                )
                              }
                            />
                            <label htmlFor={answer.id}>{answer.text}</label>
                          </li>
                        )
                      )}
                  </ul>
                  <div className="toggle-buttons">
                    <button
                      className="prev-question-button"
                      onClick={() => handleQuestionNavigation('prev')}
                    >
                      {'<'}-- Önceki Soru
                    </button>
                    <div className="question-index center-content">
                      {currentQuestionIndex + 1} / {test.questions.length}
                    </div>
                    {currentQuestionIndex !== test.questions.length - 1 ? (
                      <button
                        className="next-question-button"
                        onClick={() => handleQuestionNavigation('next')}
                      >
                        Sonraki Soru --{'>'}
                      </button>
                    ) : (
                      <button className="submit-answers" onClick={handleSubmit}>
                        Yanıtları Gönder
                      </button>
                    )}
                  </div>
                </>
              ) : (
                <p>Test soruları bulunamadı.</p>
              )}
            </div>
          </div>
        ) : (
          <div className="test-group-container">
            {Object.keys(categorizedTests).map((level) => (
              <div className="test-group" key={level}>
                <h2>Seviye {level.toUpperCase()}</h2>
                <ul className="test-group-list">
                  {categorizedTests[level].map((test) => (
                    <Link
                      className="test-group-item"
                      key={test.id}
                      to={`/exercises/${test.slug}`}
                    >
                      {test.name}
                    </Link>
                  ))}
                </ul>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

export default ExercisesPage;
