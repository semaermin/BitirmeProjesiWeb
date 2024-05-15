import Navbar from '../components/Navbar';
import { useState, useEffect } from 'react';
import axios from 'axios';
import VideoBox from '../components/VideoBox';

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
      <VideoBox></VideoBox>
      {/* <div>
        {videoQuestions && videoQuestions.length > 0 ? (
          videoQuestions.map((question) => (
            <div key={question.id}>
              <h3>
                {question.text} - Soru Süresi {question.duration}
              </h3>
              {question.media_path && (
                <video controls src={question.media_path}></video>
              )}
              <ul>
                {question.answers &&
                  question.answers.map((answer, index) => (
                    <li key={index}>{answer.text}</li>
                  ))}
              </ul>
            </div>
          ))
        ) : (
          <p>Sistemde hiç video sorusu yok</p>
        )}
      </div> */}
    </div>
  );
}

export default VideoPage;
