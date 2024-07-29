import '../assets/styles/components/video-box.scss';
import { useTheme } from '../context/ThemeContext';
import { useState, useEffect } from 'react';
import useUpdateUserPoints from '../utils/UseUpdateUserPoints.js';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import { XCircleFill } from 'react-bootstrap-icons';
import { RotatingLines } from 'react-loader-spinner';

export default function VideoBox() {
  const { theme, user, setUser } = useTheme();
  const [videoQuestion, setVideoQuestion] = useState(null); // Başlangıçta null olarak ayarlayın
  const updateUserPoints = useUpdateUserPoints();

  useEffect(() => {
    fetchVideoQuestion();
  }, []);

  const fetchVideoQuestion = async () => {
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_API_URL}/video-list`
      );
      setVideoQuestion(response.data.videoQuestion); // Videolu soruyu güncelle
    } catch (error) {
      console.error('Error fetching video question:', error);
    }
  };

  const handleSubmit = async (questionId, answerId, videoQuestionId) => {
    try {
      const answers = [{ questionId, answerId }];

      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/api/check-video-answers`,
        {
          userUUID: user.uuid,
          questionId: questionId,
          answerId: answerId,
        }
      );
      console.log(response.data.message);
      if (response.data.totalPoints > 0) {
        toast.success(`Tebrikler ${response.data.totalPoints} puan kazandın 👏🏻`);
        updateUserPoints(response.data.userPoint);
    } else {
        toast.error('Maalesef yanlış cevap verdin ve puan kazanamadın!', {
            icon: (
                <XCircleFill
                    width="20px"
                    height="20px"
                    style={{ color: '#b93333' }}
                />
            ),
        });
    }

      fetchVideoQuestion();
      updateUserPoints(response.data.userPoint);
    } catch (error) {
      console.error('Yanıtlar gönderilemedi:', error.message);
    }
  };

  if (!videoQuestion) {
    return (
      <div className="loading-screen">
        <RotatingLines
          visible={true}
          height="36"
          width="36"
          strokeWidth="5"
          animationDuration="0.75"
          ariaLabel="rotating-lines-loading"
        />
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
                src={`${import.meta.env.VITE_API_URL}/storage/${
                  videoQuestion.media_path
                }`}
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
      <ToastContainer
        position="bottom-right"
        autoClose={4000}
        limit={8}
        hideProgressBar={false}
        newestOnTop
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme={theme}
      />
    </div>
  );
}
