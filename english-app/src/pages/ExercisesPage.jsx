import Navbar from '../components/Navbar';
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import '../assets/styles/exercies-page.scss';

function ExercisesPage() {
  const [test, setTest] = useState({});
  const [tests, setTests] = useState([]);
  const { slug } = useParams();
  const { theme } = useTheme();

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
        console.log(response.data);
      } else {
        console.error('Failed to fetch test details:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test details:', error.message);
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
        console.log(response.data.tests);
      } else {
        console.error('Failed to fetch test list:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test list:', error.message);
    }
  }

  // Testleri language_level'e göre kategorize et
  const categorizedTests = ['a1', 'a2', 'b1'].reduce((acc, level) => {
    const filteredTests = tests.filter((test) => test.language_level === level);
    if (filteredTests.length > 0) {
      acc[level] = filteredTests;
    }
    return acc;
  }, {});

  return (
    <div>
      <Navbar item="exercises" />
      <div className={theme}>
        {slug && test.name ? (
          <div>
            <h2>Test Adı: {test.name}</h2>
            {test.questions && test.questions.length > 0 ? (
              test.questions.map((question, index) => (
                <div key={index} className="mb-4">
                  <h5>
                    {index + 1}.) {question.text} - Soru Süresi{' '}
                    {question.duration} saniye
                  </h5>
                  {question.media_path ? (
                    <img
                      src={question.media_path}
                      alt="Soru Resmi"
                      width="200"
                    />
                  ) : (
                    <p>Soru için fotoğraf yok</p>
                  )}
                  <ul className="list-group list-group-flush mt-2">
                    {question.answers &&
                      question.answers.length > 0 &&
                      question.answers.map((answer, answerIndex) => (
                        <li className="list-group-item" key={answerIndex}>
                          {answer.text} -{' '}
                          {answer.is_correct ? (
                            <span className="text-success">Doğru Cevap</span>
                          ) : (
                            <span className="text-danger">Yanlış Cevap</span>
                          )}
                        </li>
                      ))}
                  </ul>
                </div>
              ))
            ) : (
              <p>Test soruları bulunamadı.</p>
            )}
          </div>
        ) : (
          <div className="test-group-container">
            {Object.keys(categorizedTests).map((level) => (
              <div className="test-group" key={level}>
                <h2>Seviye {level.toUpperCase()}</h2>
                <ul className="list-group">
                  {categorizedTests[level].map((test) => (
                    <li key={test.id} className="list-group-item">
                      <Link to={`/exercises/${test.slug}`}>{test.name}</Link>
                    </li>
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
