import '../assets/styles/forgotpassword.scss';
import { useTheme } from '../context/ThemeContext';
import { Link } from 'react-router-dom';

function ForgotPasswordPage() {
  const { theme } = useTheme();

  return (
    <div className={theme}>
      <div className="password-container">
        <div className="password-left">
          <div className="password-left-image" />
          <Link to="/login">
            {' '}
            <img
              className="sermify-logo"
              src="/src/assets/images/svg/logo-white.svg"
              alt="sermify-white-logo"
            />
          </Link>
        </div>
        <div className="password-right">
          <div className="password">
            <div className="password-right-head">
              <h4>Şifremi Unuttum</h4>
              <img
                className="sermify-logo-mobile"
                src="/src/assets/images/svg/logo-red.svg"
                alt="sermify-red-logo-mobile"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default ForgotPasswordPage;
