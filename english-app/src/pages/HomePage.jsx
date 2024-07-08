import { useState, useEffect } from 'react';
import Navbar from '../components/Navbar';
import { useNavigate } from 'react-router-dom';
import Leaderboard from '../components/Leaderboard';
import UserProfile from '../components/UserProfile';
import AccuracyRateTests from '../components/AccuracyRateTests';

function HomePage() {
  const navigate = useNavigate();
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  // const [tests, setTests] = useState([]);

  useEffect(() => {
    checkUserLoggedIn();
  }, []);

  // useEffect(() => {
  //   if (isLoggedIn) {
  //     fetchTestList();
  //   }
  // }, [isLoggedIn]);

  function checkUserLoggedIn() {
    const token = localStorage.getItem('token');
    if (token) {
      setIsLoggedIn(true);
    } else {
      setIsLoggedIn(false);
      navigate('/login');
    }
  }

  // async function fetchTestList() {
  //   try {
  //     const response = await fetch(`${import.meta.env.VITE_API_URL}/test-list`, {
  //       headers: {
  //         Authorization: `Bearer ${localStorage.getItem('token')}`,
  //       },
  //     });
  //     if (response.ok) {
  //       const data = await response.json();
  //       setTests(data.tests);
  //     } else {
  //       console.error('Failed to fetch test list:', response.statusText);
  //     }
  //   } catch (error) {
  //     console.error('Failed to fetch test list:', error);
  //   }
  // }

  return (
    <div>
      <Navbar item="home"></Navbar>
      <UserProfile></UserProfile>
      <AccuracyRateTests></AccuracyRateTests>
      <Leaderboard recordsPerPage={5}></Leaderboard>
    </div>
  );
}

export default HomePage;
