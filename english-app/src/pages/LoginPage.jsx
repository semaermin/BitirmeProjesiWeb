import '../assets/styles/login.scss';
import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import { useNavigate } from 'react-router-dom';
import { login, setAxiosInterceptors } from '../services/LoginService';

function LoginPage() {
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { setUser } = useTheme();

  useEffect(() => {
    checkUserLoggedIn();
  }, []);

  const handleSubmit = async () => {
    event.preventDefault();

    try {
      const userInfo = await login(email, password);
      localStorage.setItem('token', userInfo?.token);
      setAxiosInterceptors(userInfo?.token);
      setUser(userInfo?.user);
      localStorage.setItem('user', JSON.stringify(userInfo?.user));
      navigate('/home');
    } catch (error) {
      console.error('Giriş hatası:', error);
    }
  };

  function checkUserLoggedIn() {
    const token = localStorage.getItem('token');
    if (token) {
      navigate('/home');
    }
  }

  const togglePassword = (event) => {
    if (event.key !== 'Enter') {
      setShowPassword(!showPassword);
    }
  };

  // eslint-disable-next-line no-unused-vars
  const handleKeyDown = (event) => {
    if (event.key === 'Enter') {
      handleSubmit(event);
    }
  };

  return (
    <div>
      <div className="login-container">
        <div className="login-left">
          <div className="login-left-image" />
          <Link to="/login">
            <img
              className="sermify-logo"
              src="/src/assets/images/svg/logo-white-smile-text.svg"
              alt="sermify-white-logo"
            />
          </Link>
        </div>
        <div className="login-right">
          <div className="login">
            <div className="login-right-head">
              <h4>Giriş Yap</h4>
              <img
                className="sermify-logo-mobile"
                src="/src/assets/images/svg/logo-red-smile-text.svg"
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
                onChange={(e) => setEmail(e.target.value)}
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
                  onChange={(e) => setPassword(e.target.value)}
                />
                <button
                  type="button"
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
                onClick={handleSubmit}
              />
            </form>
            <Link to="/forgot-password">
              <input
                className="remember-password"
                type="button"
                value="Şifrenizi mi unuttunuz?"
              />
            </Link>
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
