import { useState, useEffect } from 'react';
import Navbar from '../components/Navbar';
import { useNavigate } from 'react-router-dom';
import Leaderboard from '../components/Leaderboard';
import UserProfile from '../components/UserProfile';
import AccuracyRateTests from '../components/AccuracyRateTests';
import { Helmet } from 'react-helmet-async';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

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
    <div className="homepage">
      <Helmet>
        <meta
          name="description"
          content="Sermify'daki 10-15 saniyelik kısa videolar ve seviyene göre İngilizce testlerle İngilizceni hızlı ve eğlenceli bir şekilde geliştir."
        />
        <meta property="og:type" content="website" />
        <meta
          property="og:title"
          content="Sermify'la İngilizcenizi geliştirmeye devam edin."
        />
        <meta
          property="og:description"
          content="Sermify ile İngilizcenizi 10-15 saniyelik kısa videolar ve eğlenceli testlerle geliştirin. Hemen başlayın!"
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:url" content="https://www.sermify.com.tr/home" />
        <link rel="canonical" href="https://www.sermify.com.tr/home" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Ana Sayfa | Sermify</title>
      </Helmet>
      <Navbar item="home"></Navbar>
      <UserProfile></UserProfile>
      <AccuracyRateTests></AccuracyRateTests>
      <Leaderboard recordsPerPage={5}></Leaderboard>
      <ToastContainer />
    </div>
  );
}

export default HomePage;
