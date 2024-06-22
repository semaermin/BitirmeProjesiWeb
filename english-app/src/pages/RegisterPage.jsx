import '../assets/styles/register.scss';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';
import { Helmet } from 'react-helmet';
import SignIn from '../pages/SignIn';
import axios from 'axios';

function RegisterPage() {
  const navigate = useNavigate();
  const [showPassword, setShowPassword] = useState(false);
  const [showPassword2, setShowPassword2] = useState(false);
  const [password1, setPassword1] = useState('');
  const [password2, setPassword2] = useState('');
  const [passwordsMatch, setPasswordsMatch] = useState(true);
  const [userData, setUserData] = useState({
    name: '',
    email: '',
    password: '',
  });
  const { setUser } = useTheme();

  const isPasswordValid = (password) => {
    return (
      password.length >= 8 &&
      /[A-Z]/.test(password) &&
      /[a-z]/.test(password) &&
      /\d/.test(password) &&
      /[!@#$%^&*(),.?":{}|<>]/.test(password)
    );
  };

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

  const handlePasswordMatch = () => {
    if (password1 === password2) {
      setPasswordsMatch(true);
    } else {
      setPasswordsMatch(false);
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

    // Process name input to capitalize first letters
    const processedValue =
      name === 'name' ? capitalizeFirstLetter(value) : value;

    setUserData((prevState) => ({
      ...prevState,
      [name]: processedValue,
    }));
  };

  const handleSubmit = async (e) => {
    handlePasswordMatch();
    e.preventDefault();

    if (!passwordsMatch) {
      return; // Şifreler eşleşmiyorsa kayıt işlemini gerçekleştirme
    } else if (!isPasswordValid(password1)) {
      alert(
        'Şifreniz en az 8 karakter uzunluğunda olmalıdır ve en az 1 büyük harf, 1 küçük harf, 1 rakam ve şu(?, _, @, !, #, %, +, -, *, $, &, .) özel karakterlerden birini içermelidir.'
      );
      return;
    }

    try {
      // userData'daki password alanını kullanarak güncelle
      const updatedUserData = { ...userData, password: password1 };
      const response = await axios.post(
        'http://127.0.0.1:8000/user/register',
        updatedUserData
      );
      if (response.status === 201) {
        // Kullanıcı kaydı başarılı olduysa sunucudan gelen token'ı al
        const token = response.data.token;
        const userInfo = response.data;

        setUser(userInfo?.user);
        localStorage.setItem('user', JSON.stringify(userInfo?.user));
        // Token'ı localStorage'a kaydet
        localStorage.setItem('token', token);
        navigate('/login');
      }
    } catch (error) {
      if (error.response) {
        console.log(error);
        if (
          error.response.status === 422 &&
          error.response.data.errors.email[0] ===
            'The email has already been taken.'
        ) {
          alert(
            'Bu email adresi zaten kullanımda! Lütfen farklı bir email adresi almayı deneyiniz.'
          );
        }
        console.error('Sunucu hatası:', error.response.data);
      } else if (error.request) {
        // İstek yapılamadığında
        console.error('İstek yapılamadı:', error.request);
      } else {
        console.error('Hata oluştu:', error.message);
      }
    }
  };

  return (
    <div className="register-container">
      {/* <Helmet>
        <title>Sermify | Hesap Oluşturma Sayfası</title>
        <meta name="description" content="Sermify Giriş Sayfası" />
      </Helmet> */}
      <div className="register-left">
        <div className="register-left-image" />
        <Link to="/">
          <img
            className="sermify-logo"
            src="/src/assets/images/svg/logo-white-smile-text.svg"
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
                src="/src/assets/images/svg/logo-red-smile-text.svg"
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
                onBlur={handlePasswordMatch}
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
            {!passwordsMatch && (
              <p className="error-message">Şifreler eşleşmiyor.</p>
            )}
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
            <img
              src="/src/assets/images/svg/google-logo.svg"
              alt="google-logo"
            />
          </span>
          <SignIn></SignIn>
        </div>
      </div>
    </div>
  );
}

export default RegisterPage;
