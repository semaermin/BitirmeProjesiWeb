import React, { useEffect, useState } from 'react';
import '../assets/styles/components/accuracy-rate-test.scss';
import { useTheme } from '../context/ThemeContext';
import {
  CheckCircleFill,
  FileEarmarkCheckFill,
  XCircleFill,
} from 'react-bootstrap-icons';
import { Link } from 'react-router-dom';

export default function AccuracyRateTests() {
  const { theme } = useTheme();
  const [testResults, setTestResults] = useState(null);

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
      const userId = user.id;

      const response = await fetch(
        `http://127.0.0.1:8000/api/user-test-results/${userId}`,
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
          'Failed to fetch user test results:',
          response.statusText
        );
      }
    } catch (error) {
      console.error('Failed to fetch user test results:', error);
    }
  }

  return (
    <div className={theme}>
      {testResults && (
        <div className="accuracy-rate-tests-container">
          <h3>
            <FileEarmarkCheckFill></FileEarmarkCheckFill>Doğruluk Oranı
          </h3>
          <div className="accuracy-rate-box">
            <span className="accuracy-rate">
              <CheckCircleFill></CheckCircleFill>%
              {testResults.totalCorrectPercentage.toFixed(2)}
            </span>
            <span className="inaccuracy-rate">
              <XCircleFill></XCircleFill>%
              {testResults.totalIncorrectPercentage.toFixed(2)}
            </span>
          </div>
          <Link to="/exercises">
            <button>Seriye Devam!</button>
          </Link>
        </div>
      )}
    </div>
  );
}
