import '../assets/styles/register.scss';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';

function RegisterPage() {
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
        // Önceki sayfa bilgisi yoksa, varsayılan olarak login yönlendir
        navigate('/login');
      }
    }
  }

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

  const togglePassword = () => {
    setShowPassword(!showPassword);
  };

  const togglePassword2 = () => {
    setShowPassword2(!showPassword2);
  };

  const handlePasswordMatch = () => {
    if (password1 === password2) {
      setPasswordsMatch(true);
    } else {
      setPasswordsMatch(false);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;

    // Diğer inputlar için userData state'ini güncelle
    setUserData((prevState) => ({
      ...prevState,
      [name]: value,
    }));

    // Şifre alanı ise password1 veya password2 state'ine atama yap
    if (name === 'password') {
      setPassword1(value);
    } else if (name === 'password2') {
      setPassword2(value);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
  
    // Şifrelerin eşleşip eşleşmediğini kontrol et
    handlePasswordMatch();
  
    // Şifreler eşleşmiyorsa kayıt işlemini gerçekleştirme
    // if (!passwordsMatch) {
    //   return;
    // }
  
    try {
      // userData'daki password alanını kullanarak güncelle
      const updatedUserData = { ...userData, password: password1 };
  
      const response = await axios.post(
        'http://127.0.0.1:8000/user/register',
        updatedUserData
      );
      console.log(response.data);
      if (response.status === 201) {
        // Kullanıcı kaydı başarılı olduysa sunucudan gelen token'ı al
        const token = response.data.token;
  
        // Token'ı localStorage'a kaydet
        localStorage.setItem('token', token);
  
        // Konsola token'ı yaz
        console.log('Token:', token);
  
        // Giriş sayfasına yönlendir
        navigate('/login');
      }
    } catch (error) {
      if (error.response) {
        // Sunucudan gelen hata yanıtını kontrol edin
        console.error('Sunucu hatası:', error.response.data);
      } else if (error.request) {
        // İstek yapılamadığında
        console.error('İstek yapılamadı:', error.request);
      } else {
        // Diğer hata durumları
        console.error('Hata oluştu:', error.message);
      }
    }
  };
  

  return (
    <div className="register-container">
      <div className="register-left">
        <div className="register-left-image" />
        <Link to="/login">
          <img
            className="sermify-logo"
            src="/src/assets/images/svg/logo-white.svg"
            alt="logo-white"
          />
        </Link>
      </div>
      <div className="register-right">
        <div className="register">
          <div className="register-right-head">
            <h4>Hesap Oluştur</h4>
            <img
              className="sermify-logo-mobile"
              src="/src/assets/images/svg/logo-red.svg"
              alt="logo-red"
            />
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
          Google ile Giriş Yap
        </div>
      </div>
    </div>
  );
}

export default RegisterPage;
