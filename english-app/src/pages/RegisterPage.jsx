import '../assets/styles/register.scss';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { Helmet } from 'react-helmet-async';
import SignIn from '../pages/SignIn';
import axios from 'axios';
import { ToastContainer, toast } from 'react-toastify';
import { isPasswordValid } from '../utils/PasswordGenerationControl';

import logoWhiteSmileText from '../assets/images/svg/logo-white-smile-text.svg';
import logoRedSmileText from '../assets/images/svg/logo-red-smile-text.svg';
import googleLogo from '../assets/images/svg/google-logo.svg';

function RegisterPage() {
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [showPassword2, setShowPassword2] = useState(false);
  const [password1, setPassword1] = useState('');
  const [password2, setPassword2] = useState('');
  const [userData, setUserData] = useState({
    name: '',
    email: '',
    password: '',
  });
  const { setUser } = useTheme();

  const togglePassword = (event) => {
    if (event.key !== 'Enter') {
      setShowPassword(!showPassword);
    }
  };

  const togglePassword2 = (event) => {
    if (event.key !== 'Enter') {
      setShowPassword2(!showPassword2);
    }
  };

  const capitalizeFirstLetter = (string) => {
    return string
      .split(' ')
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
      .join(' ');
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    const processedValue =
      name === 'name' ? capitalizeFirstLetter(value) : value;
    setUserData((prevState) => ({
      ...prevState,
      [name]: processedValue,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (password1 !== password2) {
      toast.warn(
        'Şifreleriniz eşleşmiyor, lütfen girdiğiniz iki parolanında aynı olduğundan emin olunuz.'
      );
      return;
    } else if (!isPasswordValid(password1)) {
      toast.warn(
        'Şifreniz en az 8 karakter uzunluğunda olmalıdır ve en az 1 büyük harf, 1 küçük harf, 1 rakam ve şu   (?, _, @, !, #, %, +, -, *, $, &, .) özel karakterlerden birini içermelidir.'
      );
      return;
    }

    try {
      const updatedUserData = { ...userData, password: password1 };
      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/user/register`,
        updatedUserData
      );
      if (response.status === 201) {
        const token = response.data.token;
        const userInfo = response.data;
        setUser(userInfo?.user);
        localStorage.setItem('user', JSON.stringify(userInfo?.user));
        localStorage.setItem('token', token);
        navigate('/login');
      }
    } catch (error) {
      if (
        error.response.status === 422 &&
        error.response.data.errors.email[0] ===
          'The email has already been taken.'
      ) {
        toast.warn(
          'Bu email adresi zaten kullanımda! Lütfen farklı bir email adresi almayı deneyiniz.'
        );
      } else if (error.response) {
        toast.error('Sunucu hatası!');
      } else if (error.request) {
        toast.error('İstek yapılamadı!');
      } else {
        toast.error('Hata oluştu!');
      }
    }
  };

  return (
    <div className="register-container">
      <Helmet>
        <meta
          name="description"
          content="Sermify hesabı oluşturarak İngilizce öğrenmeye başlayın. Sermify'deki kısa videolar ve testler ile İngilizcenizi geliştirin."
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Hesap Oluştur" />
        <meta
          property="og:description"
          content="Sermify hesabı oluşturarak İngilizce öğrenmeye başlayın. Hemen kayıt olun ve kısa videolar ile İngilizcenizi geliştirin."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:url" content="https://www.sermify.com.tr/register" />
        <link rel="canonical" href="https://www.sermify.com.tr/register" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Hesap Oluştur | Sermify</title>
      </Helmet>
      <div className="register-left">
        <div className="register-left-image" />
        <Link to="/">
          <img
            className="sermify-logo"
            src={logoWhiteSmileText}
            alt="logo-white"
            title="Sermify Ana Sayfa"
          />
        </Link>
      </div>
      <div className="register-right">
        <div className="register">
          <div className="register-right-head">
            <h4>Hesap Oluştur</h4>
            <Link to="/">
              <img
                className="sermify-logo-mobile"
                src={logoRedSmileText}
                alt="logo-red"
              />
            </Link>
          </div>
          <form onSubmit={handleSubmit}>
            <label className="register-label" htmlFor="name-surname">
              Ad Soyad
            </label>
            <input
              className="register-input"
              id="name-surname"
              type="text"
              name="name"
              value={userData.name}
              placeholder="John Doe"
              required
              onChange={handleChange}
            />
            <label className="register-label" htmlFor="email">
              E-posta Adresi
            </label>
            <input
              className="register-input"
              id="email"
              type="email"
              name="email"
              value={userData.email}
              onChange={handleChange}
              placeholder="johndoe@example.com"
              required
            />
            <label className="register-label" htmlFor="password">
              Parola
            </label>
            <div className="register-password-wrapper">
              <input
                className="register-input"
                id="password"
                type={showPassword ? 'text' : 'password'}
                placeholder="Parolanız"
                onChange={(e) => setPassword1(e.target.value)}
                name="password"
                value={password1}
                required
              />
              <button
                className="show-password-eye"
                onClick={togglePassword}
                type="button"
                tabIndex="-1"
              >
                {showPassword ? <Eye /> : <EyeSlash />}
              </button>
            </div>
            <label className="register-label" htmlFor="password2">
              Parola (Tekrar)
            </label>
            <div className="register-password-wrapper">
              <input
                className="register-input"
                id="password2"
                name="password2"
                value={password2}
                type={showPassword2 ? 'text' : 'password'}
                placeholder="Parolanızı tekrar girin"
                onChange={(e) => setPassword2(e.target.value)}
                required
              />
              <button
                className="show-password-eye"
                onClick={togglePassword2}
                type="button"
                tabIndex="-1"
              >
                {showPassword2 ? <Eye /> : <EyeSlash />}
              </button>
            </div>
            <input
              className="register-button"
              type="submit"
              value="Hesap Oluştur"
            />
          </form>
          <span className="have-account-box">
            Hesabınız var mı?
            <Link to="/login">
              <input
                className="have-account"
                type="button"
                value="Giriş yapın"
              />
            </Link>
          </span>
        </div>
        <p className="text-or">Veya</p>
        <div className="google-login">
          <span>
            <img src={googleLogo} alt="google-logo" />
          </span>
          <SignIn></SignIn>
        </div>
      </div>
      <ToastContainer
        position="bottom-right"
        autoClose={false}
        limit={8}
        hideProgressBar={false}
        newestOnTop
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

export default RegisterPage;
