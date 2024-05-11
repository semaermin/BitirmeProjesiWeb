import Navbar from '../components/navbar';
import { useState, useEffect } from 'react';
import axios from 'axios';

function VideoPage() {
  const [videoQuestions, setVideoQuestions] = useState([]);

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

  return (
    <div>
      <Navbar item="video"></Navbar>
      <div>
        {videoQuestions && videoQuestions.length > 0 ? (
          videoQuestions.map((question) => (
            <div key={question.id}>
              <h3>{question.text} - Soru Süresi {question.duration}</h3>
              {question.media_path && <video controls src={question.media_path}></video>}
              <ul>
                {question.answers && question.answers.map((answer, index) => (
                  <li key={index}>{answer.text}</li>
                ))}
              </ul>
            </div>
          ))
        ) : (
          <p>Video soruları yükleniyor...</p>
        )}
      </div>
    </div>
  );
}

export default VideoPage;
