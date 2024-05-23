import { useState, useEffect } from 'react';
import Navbar from '../components/Navbar';
import { useNavigate } from 'react-router-dom';
import { Link } from 'react-router-dom';
import Leaderboard from '../components/Leaderboard';

function HomePage() {
  const navigate = useNavigate();
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [tests, setTests] = useState([]);

  useEffect(() => {
    // Sayfa yüklendiğinde oturum durumunu kontrol et
    checkUserLoggedIn();
  }, []);

  useEffect(() => {
    fetchTestList();
  }, []);

  function checkUserLoggedIn() {
    // Token'i localStorage'da sakla
    const token = localStorage.getItem('token');
    if (token) {
      // Eğer token varsa, kullanıcı oturumu açık demektir
      setIsLoggedIn(true);
    } else {
      setIsLoggedIn(false);

      // Oturum açık değilse, kullanıcıyı login sayfasına yönlendir
      navigate('/login');
    }
  }
  async function fetchTestList() {
    try {
      const response = await fetch('http://127.0.0.1:8000/test-list', {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
      if (response.ok) {
        const data = await response.json();
        setTests(data.tests);
        console.log(tests);
      } else {
        console.error('Failed to fetch test list:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test list:', error);
    }
  }

  return (
    <div>
      <Navbar item="home"></Navbar>
      <Leaderboard recordsPerPage={5}></Leaderboard>
      <ul>
        {tests.map((test) => (
          <li key={test.id}>
            <Link to={`/exercises/${test.slug}`}>
              {test.id} - {test.name}
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default HomePage;
