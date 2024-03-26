import '../assets/styles/login.scss';
import '../assets/styles/register.scss';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEyeSlash } from '@fortawesome/free-regular-svg-icons';
import { useState } from 'react';
import { Link } from 'react-router-dom';

function RegisterPage() {
  const [showPassword, setShowPassword] = useState(false);
  const [showPassword2, setShowPassword2] = useState(false);

  const togglePassword = () => {
    setShowPassword(!showPassword);
  };

  const togglePassword2 = () => {
    setShowPassword2(!showPassword2);
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
          <label className="register-label" htmlFor="email">
            E-posta Adresi
          </label>
          <input
            className="register-input"
            id="email"
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
              id="password"
              type={showPassword2 ? 'text' : 'password'}
              placeholder="Parolanız"
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
