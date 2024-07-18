import React, { useState } from 'react';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import { Link } from 'react-router-dom';
import '../assets/styles/forgot-password.scss';
import { Helmet } from 'react-helmet-async';
import logoWhiteSmileText from '../assets/images/svg/logo-white-smile-text.svg';
import logoRedSmileText from '../assets/images/svg/logo-red-smile-text.svg';
import { ToastContainer, toast } from 'react-toastify';

function ForgotPasswordPage() {
  const { theme } = useTheme();
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/api/forgot-password`,
        { email }
      );
      toast.success(
        'Sıfırlama bağlantısı E-posta adresinize başarıyla gönderildi.'
      );
    } catch (error) {
      error.response.status === 404
        ? toast.error('Bu E-posta adresi sistemde kayıtlı değil.')
        : '';
    }
  };

  return (
    <div className={theme}>
      <Helmet>
        <meta
          name="description"
          content="Sermify şifrenizi unuttuysanız, buradan mail adresinizi yazıp gelen şifre sıfırlama bağlantısı ile şifrenizi sıfırlayabilirsiniz."
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Şifremi Unuttum Sayfası" />
        <meta
          property="og:description"
          content="Sermify şifrenizi unuttuysanız, buradan mail adresinizi yazıp gelen şifre sıfırlama bağlantısı ile şifrenizi sıfırlayabilirsiniz."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta
          property="og:url"
          content="https://www.sermify.com.tr/forgot-password"
        />
        <link
          rel="canonical"
          href="https://www.sermify.com.tr/forgot-password"
        />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Şifremi Unuttum | Sermify</title>
      </Helmet>
      <div className="password-container">
        <div className="password-left">
          <div className="password-left-image" />
          <Link to="/">
            <img
              className="sermify-logo"
              src={logoWhiteSmileText}
              alt="sermify-white-logo"
              title="Sermify Ana Sayfa"
            />
          </Link>
        </div>
        <div className="password-right">
          <div className="password">
            <div className="password-right-head">
              <h4>Şifremi Unuttum</h4>
              <Link to="/">
                <img
                  className="sermify-logo-mobile"
                  src={logoRedSmileText}
                  alt="sermify-red-logo-mobile"
                />
              </Link>
              <form onSubmit={handleSubmit}>
                <label className="password-label" htmlFor="email">
                  E-posta Adresi
                </label>
                <input
                  className="remember-password-input"
                  type="email"
                  name="email"
                  value={email}
                  placeholder="johndoe@example.com"
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
                <button className="remember-password-button" type="submit">
                  Sıfırlama Bağlantısı Gönder
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <ToastContainer
        position="bottom-right"
        autoClose={5000}
        limit={8}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme={'light'}
      />
    </div>
  );
}

export default ForgotPasswordPage;
