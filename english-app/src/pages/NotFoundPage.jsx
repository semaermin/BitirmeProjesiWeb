import '../assets/styles/not-found.scss';
import { Link } from 'react-router-dom';
import { useTheme } from '../context/ThemeContext';

function NotFoundPage() {
  const { theme } = useTheme();

  return (
    <div id="notfound" className={theme}>
      <div className="not-found-wrapper">
        <img
          src="/src/assets/images/svg/404-not-found.svg"
          alt="404-not-found"
        />
        <p>Ooops! Sanırım kayboldunuz.</p>
        <Link to="home">
          <input type="button" value="Ana Sayfaya Geri Dön" />
        </Link>
      </div>
    </div>
  );
}

export default NotFoundPage;
