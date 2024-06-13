import '../assets/styles/components/video-box.scss';
import { useTheme } from '../context/ThemeContext';
import { useState, useEffect } from 'react';
import axios from 'axios';

export default function VideoBox() {
  const { theme } = useTheme();
  const [videoQuestions, setVideoQuestions] = useState([]);
  const [currentVideoIndex, setCurrentVideoIndex] = useState(0);

  useEffect(() => {
    fetchVideoQuestions();
  }, []);

  const fetchVideoQuestions = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/video-list');
      setVideoQuestions(response.data.videoQuestions); // Videolu soruları güncelle
    } catch (error) {
      console.error('Error fetching video questions:', error);
    }
  };

  const handleNextVideo = () => {
    setCurrentVideoIndex(
      (prevIndex) => (prevIndex + 1) % videoQuestions.length
    );
  };

  const currentVideo = videoQuestions[currentVideoIndex];

  console.log(currentVideo);

  return (
    <div className={theme}>
      <div className="video-container">
        {currentVideo && (
          <div className="video-box">
            <div className="video">
              <video autoPlay controls key={currentVideo.id}>
                <source
                  src={`http://localhost:8000/storage/${currentVideo.media_path}`}
                  type="video/mp4"
                />
              </video>
            </div>
            <div className="video-texts-container">
              <p className="video-text">
                {currentVideo.text}
                <button
                  onClick={handleNextVideo}
                  disabled={videoQuestions.length === 0}
                >
                  Sıradaki Video
                </button>
              </p>
              <div className="video-choices">
                {currentVideo.answers?.map((answer, index) => (
                  <button value={answer.id} id={answer.id} key={index}>
                    {answer.text}
                  </button>
                ))}
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
