import { useState, useEffect } from 'react';
import Navbar from '../components/Navbar';
import { useNavigate } from 'react-router-dom';
import Leaderboard from '../components/Leaderboard';
import UserProfile from '../components/UserProfile';

function HomePage() {
  const navigate = useNavigate();
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [tests, setTests] = useState([]);
  const [testResults, setTestResults] = useState(null);

  useEffect(() => {
    // Sayfa yüklendiğinde oturum durumunu kontrol et
    checkUserLoggedIn();
  }, []);

  useEffect(() => {
    if (isLoggedIn) {
      fetchTestList();
      fetchUserTestResults();
    }
  }, [isLoggedIn]);

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
      } else {
        console.error('Failed to fetch test list:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch test list:', error);
    }
  }

  async function fetchUserTestResults() {
    try {
      // localStorage'dan user nesnesini al ve parse et
      const user = JSON.parse(localStorage.getItem('user'));
      if (!user || !user.id) {
        console.error('User ID is null or user object is not found');
        return;
      }
      const userId = user.id;
      console.log('User ID:', userId); // Debug için kullanıcı ID'sini konsola yazdır
  
      const response = await fetch(`http://127.0.0.1:8000/api/user-test-results/${userId}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      });
  
      if (response.ok) {
        const data = await response.json();
        setTestResults(data);
      } else {
        console.error('Failed to fetch user test results:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch user test results:', error);
    }
  }
  

  return (
    <div>
      <Navbar item="home"></Navbar>
      <UserProfile></UserProfile>
      <Leaderboard recordsPerPage={5}></Leaderboard>
      {testResults && (
        <div>
          <h3>Kullanıcı Test Sonuçları</h3>
          <p>Doğru Cevap Yüzdesi: {testResults.totalCorrectPercentage.toFixed(2)}%</p>
          <p>Yanlış Cevap Yüzdesi: {testResults.totalIncorrectPercentage.toFixed(2)}%</p>
        </div>
      )}
    </div>
  );
}

export default HomePage;
