import '../assets/styles/login.scss';
import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';

function LoginPage() {
  const navigate = useNavigate();

  useEffect(() => {
    // Sayfa yüklendiğinde, oturum durumunu kontrol et
    checkUserLoggedIn();
  }, []);

  function checkUserLoggedIn() {
    // Token'i localStorage'dan al
    const token = localStorage.getItem('token');
    console.log(token);
    if (token) {
      // Eğer token varsa, kullanıcı zaten giriş yapmış demektir
      // Önceki sayfayı localStorage'dan al
      const previousPage = localStorage.getItem('previousPage');
      if (previousPage) {
        // Önceki sayfaya yönlendir
        navigate(previousPage);
      } else {
        // Önceki sayfa bilgisi yoksa, varsayılan olarak anasayfaya yönlendir
        navigate('/home');
      }
    }
  }

  const [showPassword, setShowPassword] = useState(false);
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { theme } = useTheme();
  // Token durumunu saklamak için state
  const [token, setToken] = useState('');

  const togglePassword = (event) => {
    if (event.key !== 'Enter') {
      setShowPassword(!showPassword);
    }
  };

  // Form submit işlevi
  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await axios.post('http://127.0.0.1:8000/user/login', {
        email: email,
        password: password,
      });

      console.log(response.data); // Giriş başarılıysa cevabı konsola yazdır

      if (response.status === 200) {
        // Tokeni sakla
        localStorage.setItem('token', response.data.token);

        // Ana sayfaya yönlendir
        navigate('/home');
      }
    } catch (error) {
      console.error('Giriş hatası:', error); // Hata oluşursa konsola yazdır
    }
  };

  // API istekleri için interceptor ayarı
  axios.interceptors.request.use(
    (config) => {
      // Token varsa, Authorization başlığına ekle
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

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
