import '../assets/styles/login.scss';
import '../assets/styles/register.scss';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEyeSlash } from '@fortawesome/free-regular-svg-icons';
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
        <img
          className="sermify-logo"
          src="/src/assets/images/svg/logo-white.svg"
          alt="logo-white"
        />
      </div>
      <div className="register-right">
        <div className="register">
          <h4>Hesap Oluştur</h4>
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
            <span className="show-password-eye">
              {showPassword ? (
                <FontAwesomeIcon
                  onClick={togglePassword}
                  icon={faEye}
                  className="icon"
                />
              ) : (
                <FontAwesomeIcon
                  onClick={togglePassword}
                  icon={faEyeSlash}
                  className="icon"
                />
              )}
            </span>
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
            <span className="show-password-eye">
              {showPassword2 ? (
                <FontAwesomeIcon
                  onClick={togglePassword2}
                  icon={faEye}
                  className="icon"
                />
              ) : (
                <FontAwesomeIcon
                  onClick={togglePassword2}
                  icon={faEyeSlash}
                  className="icon"
                />
              )}
            </span>
            {!passwordsMatch && (
              <p className="error-message">Şifreler eşleşmiyor.</p>
            )}
          </div>
          <input
            className="register-button"
            type="button"
            value="Hesap Oluştur"
          />
          <span className="have-account-box">
            Hesabınız var mı?
            <Link to="/">
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
