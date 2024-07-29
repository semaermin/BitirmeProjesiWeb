// Third Party Imports
import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';
import { Helmet } from 'react-helmet-async';
import { ToastContainer, toast } from 'react-toastify';

// Icon Imports
import { Eye, EyeSlash } from 'react-bootstrap-icons';

// Style Imports
import '../assets/styles/login.scss';

// Image Imports
import logoRedSmileText from '../assets/images/svg/logo-red-smile-text.svg';
import logoWhiteSmileText from '../assets/images/svg/logo-white-smile-text.svg';
import googleLogo from '../assets/images/svg/google-logo.svg';

// Specific Imports
import { useTheme } from '../context/ThemeContext';
import { login, setAxiosInterceptors } from '../services/LoginService';
import SignIn from '../pages/SignIn';

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
      if (error.response.status === 401) {
        toast.error(
          'Şifreniz ya da e-posta bilgileriniz hatalı lütfen tekrar deneyiniz!'
        );
      }
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
      <Helmet>
        <meta
          name="description"
          content="Sermify hesabınıza giriş yaparak İngilizce öğrenmeye devam edebilirsiniz. Hemen giriş yapın, kısa videolar ve testlerle ile İngilizcenizi geliştirin."
        />
        <meta property="og:type" content="website" />
        <meta
          property="og:title"
          content="Sermify hesabınıza giriş yaparak İngilizce öğrenmeye başlayabilirsiniz."
        />
        <meta
          property="og:description"
          content="Sermify hesabınıza giriş yaparak İngilizce öğrenmeye devam edebilirsiniz. Hemen giriş yapın, kısa videolar ve testlerle ile İngilizcenizi geliştirin."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:url" content="https://www.sermify.com.tr/login" />
        <link rel="canonical" href="https://www.sermify.com.tr/login" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/assets/logo-red-smile-text-DwR8ucb3.svg"
        />
        <title>Giriş Yap | Sermify</title>
      </Helmet>
      <div className="login-container">
        <div className="login-left">
          <div className="login-left-image" />
          <Link to="/">
            <img
              className="sermify-logo"
              src={logoWhiteSmileText}
              alt="sermify-white-logo"
              title="Sermify Ana Sayfa"
            />
          </Link>
        </div>
        <div className="login-right">
          <div className="login">
            <div className="login-right-head">
              <h4>Giriş Yap</h4>
              <Link to="/">
                <img
                  className="sermify-logo-mobile"
                  src={logoRedSmileText}
                  alt="sermify-red-logo-mobile"
                />
              </Link>
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
            <div className="remember-password-wrapper">
              <Link to="/forgot-password">
                <input
                  className="remember-password"
                  type="button"
                  value="Şifrenizi mi unuttunuz?"
                />
              </Link>
            </div>

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
          {/* <p className="text-or">Veya</p>
          <div className="google-login">
            <span>
              <img src={googleLogo} alt="google-logo" />
            </span>
            <SignIn></SignIn>
          </div> */}
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

export default LoginPage;
