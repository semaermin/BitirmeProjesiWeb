import Navbar from '../components/Navbar';
import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import axios from 'axios';

function ExercisesPage() {
  const [test, setTest] = useState({});
  const [tests, setTests] = useState([]);
  const { slug } = useParams();

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
      } else {
        console.error('Failed to fetch test list:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test list:', error.message);
    }
  }

  return (
    <div>
      <Navbar item="exercises"></Navbar>
      <div>
        <div>
          <div>
            <div>
              <h2>Test Adı: {test.name}</h2>
              {test.questions && test.questions.length > 0 ? (
                test.questions.map((question, index) => (
                  <div key={index}>
                    <h5>
                      {index + 1}.) {question.text} - Soru Süresi{' '}
                      {question.duration}
                    </h5>
                    <br />
                    {question.media_path ? (
                      <img
                        src={question.media_path}
                        alt="Soru Resmi"
                        width="200"
                      />
                    ) : (
                      <p>Soru için fotoğraf yok</p>
                    )}

                    <ul className="list-group list-group-flush">
                      {question.answers &&
                        question.answers.length > 0 &&
                        question.answers.map((answer, answerIndex) => (
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
                  {tests.length > 0 ? (
                    tests.map((test) => (
                      <li key={test.id}>
                        <Link to={`/exercises/${test.slug}`}>
                          {test.id} - {test.name}
                        </Link>
                      </li>
                    ))
                  ) : (
                    <li>Testler yükleniyor...</li>
                  )}
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
