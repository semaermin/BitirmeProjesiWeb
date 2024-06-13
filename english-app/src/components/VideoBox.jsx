import '../assets/styles/components/video-box.scss';
import { useTheme } from '../context/ThemeContext';
import { useState, useEffect } from 'react';
import axios from 'axios';

export default function VideoBox() {
  const { theme } = useTheme();
  const [videoQuestion, setVideoQuestion] = useState(null); // Başlangıçta null olarak ayarlayın

  useEffect(() => {
    fetchVideoQuestion();
  }, []);

  const fetchVideoQuestion = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/video-list');
      // console.log(response.data); // API yanıtını konsola yazdır
      setVideoQuestion(response.data.videoQuestion); // Videolu soruyu güncelle
    } catch (error) {
      console.error('Error fetching video question:', error);
    }
  };

  if (!videoQuestion) {
    return <p>Loading...</p>; // Eğer videoQuestion undefined veya null ise, Loading... mesajı göster
  }

  // console.log(videoQuestion);

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
              <button
                onClick={fetchVideoQuestion}
              >
                Sıradaki Video
              </button>
            </p>
            <div className="video-choices">
              {videoQuestion.answers?.map((answer, index) => (
                <button value={answer.id} id={answer.id} key={index}>
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
