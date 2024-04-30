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

  // Form submit işlevi
  const handleSubmit = (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);
    const email = formData.get('email');
    const password = formData.get('password');

    console.log('E-posta:', email, 'Şifre:', password);

    const formDataObject = {};
    formData.forEach((value, key) => {
      formDataObject[key] = value;
    });

    fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formDataObject),
    })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
        console.error('Hata:', error);
      });
  };

  const handleKeyDown = (event) => {
    if (event.key === 'Enter') {
      handleSubmit(event);
    }
  };

  return (
    <div className={theme}>
      <div className="login-container">
        <div className="login-left">
          <div className="login-left-image" />
          <img
            className="sermify-logo"
            src="/src/assets/images/svg/logo-white.svg"
            alt="sermify-white-logo"
          />
        </div>
        <div className="login-right">
          <div className="login">
            <div className="login-right-head">
              <h4>Giriş Yap</h4>
              <img
                className="sermify-logo-mobile"
                src="/src/assets/images/svg/logo-red.svg"
                alt="sermify-red-logo-mobile"
              />
            </div>
            <form onSubmit={handleSubmit}>
              <label className="login-label" htmlFor="email">
                E-posta Adresi
              </label>
              <input
                className="login-input"
                id="email"
                type="email"
                name="email"
                placeholder="johndoe@example.com"
                required
                tabIndex="1"
              />
              <label className="login-label" htmlFor="password">
                Parola
              </label>
              <div className="login-password-wrapper">
                <input
                  className="login-input"
                  id="password"
                  type={showPassword ? 'text' : 'password'}
                  name="password"
                  placeholder="Parolanız"
                  required
                  tabIndex="2"
                />
                <button
                  className="show-password-eye"
                  onClick={togglePassword}
                  tabIndex="-1"
                >
                  {showPassword ? <Eye /> : <EyeSlash />}
                </button>
              </div>
              <input
                className="login-button"
                type="submit"
                value="Giriş Yap"
                tabIndex="3"
              />
            </form>
            <input
              className="remember-password"
              type="button"
              value="Şifrenizi mi unuttunuz?"
            />
            <div className="create-account">
              <div>
                <span className="horizontal-line"></span>
                <label className="create-account">Hesabınız yok mu?</label>
                <span className="horizontal-line"></span>
              </div>
              <Link to="/register">
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
