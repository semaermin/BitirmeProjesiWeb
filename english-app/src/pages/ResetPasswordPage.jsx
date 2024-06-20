import React, { useState } from 'react';
import axios from 'axios';
import { useParams, useNavigate } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
// import '../assets/styles/reset-password.scss';

function ResetPasswordPage() {
  const { theme } = useTheme();
  const { token } = useParams();
  const navigate = useNavigate();
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Şifrelerin eşleşip eşleşmediğini kontrol et
    if (password !== passwordConfirmation) {
      setError('Şifreler eşleşmiyor.');
      return;
    }

    try {
      const response = await axios.post('http://localhost:8000/api/reset-password', {
        token,
        password,
        password_confirmation: passwordConfirmation,
      });
      setMessage(response.data.message);
      setError('');
      setTimeout(() => {
        navigate('/login'); // Şifre sıfırlandıktan sonra login sayfasına yönlendirme
      }, 3000);
    } catch (error) {
      setError('Bir hata oluştu. Lütfen tekrar deneyin.');
      setMessage('');
    }
  };

  return (
    <div className={theme}>
      <div className="reset-password-container">
        <div className="reset-password-form">
          <h4>Şifre Sıfırlama</h4>
          <form onSubmit={handleSubmit}>
            <label htmlFor="password">Yeni Şifre</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
            <label htmlFor="passwordConfirmation">Şifreyi Onayla</label>
            <input
              type="password"
              value={passwordConfirmation}
              onChange={(e) => setPasswordConfirmation(e.target.value)}
              required
            />
            {error && <p style={{ color: 'red' }}>{error}</p>}
            <button type="submit">Şifreyi Sıfırla</button>
          </form>
          {message && <p>{message}</p>}
        </div>
      </div>
    </div>
  );
}

export default ResetPasswordPage;
