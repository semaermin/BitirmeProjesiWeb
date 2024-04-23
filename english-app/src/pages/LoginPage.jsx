import '../assets/styles/login.scss';
import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { Eye, EyeSlash } from 'react-bootstrap-icons';

function LoginPage() {
  const [showPassword, setShowPassword] = useState(false);
  const { toggleTheme, theme } = useTheme();

  const togglePassword = () => {
    setShowPassword(!showPassword);
  };

  return (
    <div className={theme}>
      <div className="login-container">
        <div className="login-left">
          <div className="login-left-image" />
          {/* <button onClick={toggleTheme}>Tema Değiştir</button> */}

          <img
            className="sermify-logo"
            src="/src/assets/images/svg/logo-white.svg"
            alt="logo-white"
          />
        </div>
        <div className="login-right">
          <div className="login">
            <div className="login-right-head">
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
              type="mail"
              placeholder="johndoe@example.com"
            />
            <label className="login-label" htmlFor="password">
              Parola
            </label>
            <div className="login-password-wrapper">
              <input
                className="login-input"
                id="password"
                type={showPassword ? 'text' : 'password'}
                placeholder="Parolanız"
              />
              <span className="show-password-eye">
                {showPassword ? (
                  <Eye onClick={togglePassword} />
                ) : (
                  <EyeSlash onClick={togglePassword} />
                )}
              </span>
            </div>
            <input className="login-button" type="button" value="Giriş Yap" />
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
    </div>
  );
}

export default LoginPage;
