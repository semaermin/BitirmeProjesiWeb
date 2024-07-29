import Navbar from '../components/Navbar';
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import useUpdateUserPoints from '../utils/UseUpdateUserPoints.js';
import '../assets/styles/exercises-page.scss';
import { Helmet } from 'react-helmet-async';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';
import { XCircleFill } from 'react-bootstrap-icons';
import ContentLoader from 'react-content-loader';

function ExercisesPage() {
  const [test, setTest] = useState({});
  const [tests, setTests] = useState([]);
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [selectedAnswers, setSelectedAnswers] = useState({});
  const [shuffledAnswers, setShuffledAnswers] = useState({});
  const [loading, setLoading] = useState(true);
  const { slug } = useParams();
  const { theme, user } = useTheme();
  const updateUserPoints = useUpdateUserPoints();
  const navigate = useNavigate();

  const MyLoader = (props) => (
    <ContentLoader
      speed={2}
      width={405}
      height={350}
      viewBox="0 0 405 350"
      backgroundColor={theme === 'dark' ? '#1a1a1a' : '#f3f3f3'}
      foregroundColor={theme === 'dark' ? '#242424' : '#ecebeb'}
      {...props}
    >
      <rect x="0" y="0" rx="20" ry="20" width="405" height="350" />
    </ContentLoader>
  );

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
        console.error('Test detayları getirilemedi!');
      }
    } catch (error) {
      console.error('Test detayları getirilemedi!');
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
        console.error('Test listesi getirilemedi!');
      }
    } catch (error) {
      console.error('Test listesi getirilemedi!');
    } finally {
      setLoading(false);
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
      const unansweredIndexes = test.questions.reduce((acc, _, index) => {
        if (!(index in selectedAnswers)) {
          acc.push(index + 1); // 1 bazlı indeksleme
        }
        return acc;
      }, []);

      if (unansweredIndexes.length === 1) {
        toast.warning(`Lütfen ${unansweredIndexes}. soruyu yanıtlayın.`);
      } else if (unansweredIndexes.length >= 2) {
        toast.warning(
          `Lütfen ${unansweredIndexes.slice(0, -1).join(', ')} ${
            unansweredIndexes.length > 1 ? 've' : ''
          } ${unansweredIndexes.slice(-1)}. soruyu yanıtlayın.`
        );
      }
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
      if (response.data.totalPoints > 0) {
        navigate('/exercises');
        setSelectedAnswers('');
        toast.success(
          `Tebrikler ${response.data.totalPoints} puan kazandın 👏🏻`
        );
      } else {
        toast.error('Tüm sorulara yanlış cevap verdin ve puan kazanamadın!', {
          icon: (
            <XCircleFill
              width="20px"
              height="20px"
              style={{ color: '#e74c3c' }}
            />
          ),
        });
        toast.info('İstersen alıştırmayı tekrar çözebilirsin.');
        navigate('/exercises');
        setSelectedAnswers('');
      }

      updateUserPoints(response.data.userPoint);
    } catch (error) {
      console.error('Yanıtlar gönderilemedi!');
    }
  };

  const currentQuestion = test.questions
    ? test.questions[currentQuestionIndex]
    : null;

  return (
    <div>
      <Helmet>
        <meta
          name="description"
          content="Sermify'daki seviyene göre test alıştırmalarıyla İngilizceni hızlı ve eğlenceli bir şekilde geliştir."
        />
        <meta property="og:type" content="website" />
        <meta
          property="og:title"
          content="Sermify'daki testlerlere alıştırma yaparak İngilizceni geliştir."
        />
        <meta
          property="og:description"
          content="Sermify ile İngilizcenizi eğlenceli testlerle alıştırma yaparak geliştirin. Hemen başlayın!"
        />
        <meta property="og:locale" content="tr_TR" />
        <meta
          property="og:url"
          content="https://www.sermify.com.tr/exercises"
        />
        <link rel="canonical" href="https://www.sermify.com.tr/exercises" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Alıştırmalar | Sermify</title>
      </Helmet>
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
                <p>Bu alıştırmanın içerisinde test sorusu bulunamadı.</p>
              )}
            </div>
          </div>
        ) : loading ? (
          <div className="loader-skeleton">
            <MyLoader />
            <MyLoader />
            <MyLoader />
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
      <ToastContainer
        position="bottom-right"
        autoClose={5000}
        limit={8}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme={theme}
      />
    </div>
  );
}

export default ExercisesPage;
