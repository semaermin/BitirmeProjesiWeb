import { useState } from 'react';
import { useTheme } from '../context/ThemeContext';
import { Link } from 'react-router-dom';
import axios from 'axios';

function ForgotPasswordPage() {
  const { theme } = useTheme();
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post('http://127.0.0.1:8000/user/reset-password', { email });
      setMessage(response.data.message);
      setError('');
    } catch (error) {
      setError('Bir hata oluştu. Lütfen tekrar deneyin.');
      setMessage('');
    }
  };
  

  return (
    <div className={theme}>
      <div className="password-container">
        <div className="password-left">
          <div className="password-left-image" />
          <Link to="/login">
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
            <form onSubmit={handleSubmit}>
              <input
                type="email"
                placeholder="E-posta adresiniz"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
              />
              <button type="submit">Şifreyi Sıfırla</button>
            </form>
            {message && <p>{message}</p>}
            {error && <p>{error}</p>}
          </div>
        </div>
      </div>
    </div>
  );
}

export default ForgotPasswordPage;
