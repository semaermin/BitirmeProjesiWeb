import '../assets/styles/register.scss';
import { Eye, EyeSlash } from 'react-bootstrap-icons';
import { useState } from 'react';
import { Link } from 'react-router-dom';

function RegisterPage() {
  const [showPassword, setShowPassword] = useState(false);
  const [showPassword2, setShowPassword2] = useState(false);
  const [password1, setPassword1] = useState('');
  const [password2, setPassword2] = useState('');
  const [passwordsMatch, setPasswordsMatch] = useState(true);

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
          {/* <h4>Hesap Oluştur</h4> */}
          <label className="register-label" htmlFor="name-surname">
            Ad Soyad
          </label>
          <input
            className="register-input"
            id="name-surname"
            type="text"
            placeholder="John Doe"
          />
          <label className="register-label" htmlFor="email-2">
            E-posta Adresi
          </label>
          <input
            className="register-input"
            id="email-2"
            type="mail"
            placeholder="johndoe@example.com"
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
            />
            <button className="show-password-eye">
              {showPassword ? (
                <Eye onClick={togglePassword} />
              ) : (
                <EyeSlash onClick={togglePassword} />
              )}
            </button>
          </div>
          <label className="register-label" htmlFor="password2">
            Parola (Tekrar)
          </label>
          <div className="register-password-wrapper">
            <input
              className="register-input"
              id="password2"
              type={showPassword2 ? 'text' : 'password'}
              placeholder="Parolanız"
              onChange={(e) => setPassword2(e.target.value)}
              onBlur={handlePasswordMatch}
            />
            <button className="show-password-eye">
              {showPassword2 ? (
                <Eye onClick={togglePassword2} />
              ) : (
                <EyeSlash onClick={togglePassword2} />
              )}
            </button>
          </div>
          {!passwordsMatch && (
            <p className="error-message">Şifreler eşleşmiyor.</p>
          )}
          <input
            className="register-button"
            type="button"
            value="Hesap Oluştur"
          />
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
