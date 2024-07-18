// Third Party Imports
import { Link } from 'react-router-dom';
import { Helmet } from 'react-helmet-async';

// Specific Imports
import { useTheme } from '../context/ThemeContext';

// Style Imports
import '../assets/styles/not-found.scss';

function NotFoundPage() {
  const { theme } = useTheme();

  return (
    <div id="notfound" className={theme}>
      <Helmet>
        <meta name="description" content="Aradığınız sayfa bulunamadı." />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Sayfa Bulunamadı" />
        <meta
          property="og:description"
          content="Aradığınız sayfa bulunamadı."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:url" content="https://www.sermify.com.tr/404" />
        <link rel="canonical" href="https://www.sermify.com.tr/404" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Sayfa Bulunamadı | Sermify</title>
      </Helmet>

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
