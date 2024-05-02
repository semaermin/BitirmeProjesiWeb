import '../assets/styles/login.scss';
import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import axios from 'axios';

function LoginPage() {
  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { theme } = useTheme();

  const togglePassword = (event) => {
    if (event.key !== 'Enter') {
      setShowPassword(!showPassword);
    }
  };

  // Form submit işlevi
  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const responseToken = await axios.get('http://127.0.0.1:8000/csrf-token');
      const csrfToken = responseToken.data.token;
      console.log(csrfToken);
      const response = await axios.post('http://127.0.0.1:8000/user/login', {
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
      if (response.status === 200) {
        window.location.href = '/home'; // Ana sayfa URL'sini değiştirerek yönlendirme yapabilirsiniz
      }
    } catch (error) {
      console.error('Giriş hatası:', error); // Hata oluşursa konsola yazdır
    }

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
          <Link to="/login">
            <img
              className="sermify-logo"
              src="/src/assets/images/svg/logo-white.svg"
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
