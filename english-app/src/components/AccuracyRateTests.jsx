import React, { useEffect, useState } from 'react';
import '../assets/styles/components/accuracy-rate-test.scss';
import { useTheme } from '../context/ThemeContext';
import {
  CheckCircleFill,
  FileEarmarkCheckFill,
  XCircleFill,
} from 'react-bootstrap-icons';
import { Link } from 'react-router-dom';
import { RotatingLines } from 'react-loader-spinner';

export default function AccuracyRateTests() {
  const { theme } = useTheme();
  const [testResults, setTestResults] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchUserTestResults();
  }, []);

  async function fetchUserTestResults() {
    try {
      const user = JSON.parse(localStorage.getItem('user'));
      if (!user || !user.id) {
        console.error('User ID is null or user object is not found');
        return;
      }
      const userId = user?.id;

      const response = await fetch(
        `${import.meta.env.VITE_API_URL}/api/user-test-results/${userId}`,
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
          },
        }
      );

      if (response.ok) {
        const data = await response.json();
        setTestResults(data);
      } else {
        console.error(
          'Failed to fetch user test results!',
          response.statusText
        );
      }
    } catch (error) {
      console.error('Failed to fetch user test results!');
      setLoading(false);
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className={theme}>
      {loading ? (
        <div className={theme}>
          <div className="container-loading">
            <RotatingLines
              visible={true}
              height="36"
              width="36"
              strokeWidth="5"
              animationDuration="0.75"
              ariaLabel="rotating-lines-loading"
            />
          </div>
        </div>
      ) : (
        testResults && (
          <div className="accuracy-rate-tests-container">
            <h3>
              <FileEarmarkCheckFill /> Doğruluk Oranı
            </h3>
            <div className="accuracy-rate-box">
              <span className="accuracy-rate">
                <CheckCircleFill />%
                {typeof testResults.totalCorrectPercentage === 'number'
                  ? testResults.totalCorrectPercentage.toFixed(2)
                  : '0'}
                <br /> <br /> Doğru
              </span>
              <span className="inaccuracy-rate">
                <XCircleFill />%
                {typeof testResults.totalIncorrectPercentage === 'number'
                  ? testResults.totalIncorrectPercentage.toFixed(2)
                  : '0'}
                <br /> <br /> Yanlış
              </span>
            </div>
            <Link to="/exercises">
              <button>
                {typeof testResults.totalIncorrectPercentage === 'number'
                  ? 'Seriye Devam!'
                  : 'Hadi Alıştırma Yapmaya Başla!'}
              </button>
            </Link>
          </div>
        )
      )}
    </div>
  );
}
