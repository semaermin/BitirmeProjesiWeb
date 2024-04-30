import '../assets/styles/login.scss';
import { Link } from 'react-router-dom';
import axios from 'axios';
import { useState } from 'react';

function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
  
    try {
      const responseToken = await axios.get('http://127.0.0.1:8000/csrf-token');
      const csrfToken = responseToken.data.token;
      console.log(csrfToken);
      const response = await axios.post('http://localhost:8000/user/login', {
        email: email,
        password: password
      }, {
        headers: {
          'X-CSRF-TOKEN': csrfToken
        }
      });
  
      console.log(response.data); // Giriş başarılıysa cevabı konsola yazdır
      // Giriş başarılıysa, kullanıcıyı ana sayfaya yönlendir
      // Örnek olarak: window.location.href = '/home';
    } catch (error) {
      console.error('Giriş hatası:', error); // Hata oluşursa konsola yazdır
    }
  };
  

  return (
    <div className="login-container">
      <div className="login-left">
        <div className="login-left-image" />
        <img
          className="sermify-logo"
          src="/src/assets/images/svg/logo-white.svg"
          alt="logo-white"
        />
      </div>
      <div className="login-right">
        <div className="login">
          <div className='login-right-head'>
            <h4>Giriş Yap</h4>
            <img
              className="sermify-logo-mobile"
              src="/src/assets/images/svg/logo-red.svg"
              alt="logo-red"
            />
          </div>
          <label className="login-label" htmlFor="mail">
            E-posta Adresi
          </label>
          <input
            className="login-input"
            id="mail"
            type="email" // Doğru input tipi 'email' olmalı
            placeholder="johndoe@example.com"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          <label className="login-label" htmlFor="password">
            Parola
          </label>
          <div className="login-password-wrapper">
            <input
              className="login-input"
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)} 
              placeholder="Parolanız"
            />
          </div>
          <input className="login-button" type="button" value="Giriş Yap" onClick={handleLogin} />
          <input
            className="remember-password"
            type="button"
            value="Şifrenizi mi unuttunuz?"
          />
          <div className="create-account">
            <div>
              <span className="horizontal-line"></span>
              <label className="create-account" htmlFor="">
                Hesabınız yok mu?
              </label>
              <span className="horizontal-line"></span>
            </div>
            <Link to="/register">
              {' '}
              <input
                className="create-account-button"
                type="button"
                value="Hesap Oluştur"
              />
            </Link>
          </div>
        </div>
        <div className="register"></div>
        <p className="text-or">Veya</p>
        <div className="google-login">
          <span>
            <img
              src="/src/assets/images/svg/google-logo.svg"
              alt="google-logo"
            />
          </span>
          Google ile Giriş Yap
        </div>
      </div>
    </div>
  );
}

export default LoginPage;
