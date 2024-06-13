import Navbar from '../components/Navbar';
import { useState, useEffect } from 'react';
import { useParams, Link, useNavigate } from 'react-router-dom'; // Import useNavigate
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import '../assets/styles/exercises-page.scss';

function ExercisesPage() {
  const [test, setTest] = useState({});
  const [tests, setTests] = useState([]);
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedAnswers, setSelectedAnswers] = useState({});
  const { slug } = useParams();
  const { theme, user } = useTheme();
  const navigate = useNavigate(); // Get navigate function from useNavigate

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
        `http://127.0.0.1:8000/test-list/${slug}`
      );
      if (response.data && response.data.test) {
        setTest(response.data.test);
        setCurrentQuestionIndex(0);
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

      const response = await axios.get('http://127.0.0.1:8000/test-list', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      });
      if (response.data && response.data.tests) {
        setTests(response.data.tests);
      } else {
        console.error('Test listesi getirilemedi:', response.statusText);
      }
    } catch (error) {
      console.error('Test listesi getirilemedi:', error.message);
    }
  }

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
        'http://127.0.0.1:8000/api/check-answers',
        {
          userId: user.id,
          testId: test.id,
          answers: answers,
        }
      );

      console.log('Gönderim yanıtı:', response.data);
      alert('Yanıtlarınız gönderildi!');
      
      // Redirect to exercises page after successful submission
      navigate('/exercises');
      
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
              {currentQuestion ? (
                <>
                  <h4>{currentQuestion.text}</h4>
                  <ul className="answer-group">
                    {currentQuestion.answers &&
                      currentQuestion.answers.length > 0 &&
                      currentQuestion.answers.map((answer, answerIndex) => (
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
                      ))}
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
