import Navbar from '../components/navbar';
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';

function ExercisesPage() {
  const [test, setTest] = useState({});
  const [tests, setTests] = useState([]);
  const { slug } = useParams();

  useEffect(() => {
    fetchTestDetails(slug); // slug değişkenini fetchTestDetails fonksiyonuna geçirin
  }, [slug]);

  useEffect(() => {
    fetchTestList();
  }, []);

  async function fetchTestDetails(slug) { // slug parametresini alın
    try {
      const response = await axios.get(`http://127.0.0.1:8000/test-list/${slug}`);
      if (response.data) {
        setTest(response.data.test || {});
      } else {
        console.error('Failed to fetch test details:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test details:', error);
    }
  }

  async function fetchTestList() {
    try {
      const response = await fetch('http://127.0.0.1:8000/test-list', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
      });
      if (response.ok) {
        const data = await response.json();
        setTests(data.tests);
      } else {
        console.error('Failed to fetch test list:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test list:', error);
    }
  }

  return (
    <div>
      <Navbar item="exercises"></Navbar>
      <div className="py-12">
        <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div className="overflow-hidden bg-white shadow-xl dark:bg-gray-800 sm:rounded-lg">
            <div className="list-group list-group-flush">
              <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Test Adı: {test.name}
              </h2>
              {test.questions && test.questions.length > 0 ? (
                test.questions.map((question, index) => (
                  <div className="list-group-item" key={index}>
                    <h5 className="mb-1">{index + 1}.) {question.text} - Soru Süresi {question.duration}</h5>
                    <br />
                    {question.media_path ? (
                      <img src={question.media_path} alt="Soru Resmi" width="200" />
                    ) : (
                      <p>Soru için fotoğraf yok</p>
                    )}

                    <ul className="list-group list-group-flush">
                      {question.answers && question.answers.length > 0 && question.answers.map((answer, answerIndex) => (
                        <li className="list-group-item" key={answerIndex}>
                          {answer.text} -
                          {answer.is_correct ? (
                            <span className="text-success">Doğru Cevap</span>
                          ) : (
                            <span className="text-danger">Yanlış Cevap</span>
                          )}
                        </li>
                      ))}
                    </ul>
                    <br />
                  </div>
                ))
              ) : (
                <ul>
                {tests.map((test) => (
                  <li key={test.id}>
                    <Link to={`/exercises/${test.slug}`}> {/* Link düzenlendi */}
                      {test.id} - {test.name}
                    </Link>
                  </li>
                ))}
              </ul>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ExercisesPage;
