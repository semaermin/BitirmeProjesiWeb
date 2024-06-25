import '../assets/styles/components/video-box.scss';
import { useTheme } from '../context/ThemeContext';
import { useState, useEffect } from 'react';
import useUpdateUserPoints from '../utils/UseUpdateUserPoints.js';
import axios from 'axios';

export default function VideoBox() {
  const { theme, user, setUser } = useTheme();
  const [videoQuestion, setVideoQuestion] = useState(null); // Başlangıçta null olarak ayarlayın
  const updateUserPoints = useUpdateUserPoints();

  useEffect(() => {
    fetchVideoQuestion();
  }, []);

  const fetchVideoQuestion = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/video-list');
      setVideoQuestion(response.data.videoQuestion); // Videolu soruyu güncelle
    } catch (error) {
      console.error('Error fetching video question:', error);
    }
  };

  const handleSubmit = async (questionId, answerId, videoQuestionId) => {
    try {
      const answers = [{ questionId, answerId }];

      const response = await axios.post(
        'http://127.0.0.1:8000/api/check-answers',
        {
          userId: user.id,
          testId: videoQuestionId,
          answers: answers,
        }
      );
      fetchVideoQuestion();
      updateUserPoints(response.data.userPoint);
    } catch (error) {
      console.error('Yanıtlar gönderilemedi:', error.message);
    }
  };

  if (!videoQuestion) {
    return (
      <div className="loading-screen">
        <p>Loading...</p>
      </div>
    );
  }

  return (
    <div className={theme}>
      <div className="video-container">
        <div className="video-box">
          <div className="video">
            <video autoPlay controls key={videoQuestion.id}>
              <source
                src={`http://localhost:8000/storage/${videoQuestion.media_path}`}
                type="video/mp4"
              />
            </video>
          </div>
          <div className="video-texts-container">
            <p className="video-text">
              {videoQuestion.text}
              <button onClick={fetchVideoQuestion}>
                Sıradaki Video --{'>'}
              </button>
            </p>
            <div className="video-choices">
              {videoQuestion.answers?.map((answer, index) => (
                <button
                  value={answer.id}
                  id={answer.id}
                  key={index}
                  onClick={() =>
                    handleSubmit(
                      videoQuestion.id,
                      answer.id,
                      videoQuestion.test_id
                    )
                  }
                >
                  {answer.text}
                </button>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
