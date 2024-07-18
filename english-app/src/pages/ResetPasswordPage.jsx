// Third Party Imports
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useParams, useNavigate } from 'react-router-dom';
import { Helmet } from 'react-helmet-async';

// Specific Imports
import { useTheme } from '../context/ThemeContext';

// Style Imports
import '../assets/styles/reset-password-page.scss';

function ResetPasswordPage() {
  const { theme, setPasswordToken } = useTheme();
  const { token } = useParams();
  const navigate = useNavigate();
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  useEffect(() => {
    setPasswordToken(token);
  }, [token, setPasswordToken]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (password !== passwordConfirmation) {
      setError('Şifreler eşleşmiyor.');
      return;
    }

    try {
      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/api/reset-password`,
        {
          token,
          password,
          password_confirmation: passwordConfirmation,
        }
      );
      setMessage(response.data.message);
      setError('');
      setTimeout(() => {
        navigate('/login');
      }, 1000);
    } catch (error) {
      setError(
        'Şifreniz en az 8 karakter uzunluğunda olmalı ve en az 1 büyük harf, 1 küçük harf, 1 rakam ve 1 sembol içermelidir.'
      );
      setMessage('');
    }
  };

  return (
    <div id="resetPasswordPage" className={theme}>
      <Helmet>
        <meta
          name="description"
          content="Şifrenizi unuttuysanız, mail adresini yazıp gelen şifre sıfırlama bağlantısı ile şifrenizi sıfırlayabilirsiniz."
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Şifre Sıfırlama" />
        <meta
          property="og:description"
          content="Şifrenizi unuttuysanız, mail adresini yazıp gelen şifre sıfırlama bağlantısı ile şifrenizi sıfırlayabilirsiniz."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Şifre Sıfırlama | Sermify</title>
      </Helmet>

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
