// Third Party Imports
import {
  BoxArrowInRight,
  ChevronDown,
  Reception4,
  MoonFill,
  TrophyFill,
} from 'react-bootstrap-icons';
import { Helmet } from 'react-helmet-async';
import { Link } from 'react-router-dom';

// Style Imports
import '../assets/styles/landing-page.scss';

// Image Imports
import logoWhiteSmileText from '../assets/images/svg/logo-white-smile-text.svg';
import smileShape from '../assets/images/svg/smile-shape.svg';
import smileShapeMidGrey from '../assets/images/svg/smile-shape-mid-grey.svg';
import laptop from '../assets/images/laptop.png';
import mobile from '../assets/images/mobile.png';
import mobileWithoutText from '../assets/images/mobile-without-text.png';

function LandingPage() {
  // document.documentElement.style.setProperty('--animate-duration', '2s');

  return (
    <div>
      <Helmet>
        <meta
          name="description"
          content="Sermify'daki 10-15 saniyelik kısa videolar ve seviyene göre İngilizce testlerle İngilizceni hızlı ve eğlenceli bir şekilde geliştir."
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Tanıtım Sayfası" />
        <meta
          property="og:description"
          content="Sermify ile İngilizcenizi 10-15 saniyelik kısa videolar ve eğlenceli testlerle geliştirin. Hemen başlayın!"
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:url" content="https://www.sermify.com.tr" />
        <link rel="canonical" href="https://www.sermify.com.tr" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>Sermify</title>
      </Helmet>
      <div className="section-1">
        <nav>
          <div className="sermify-logo wow animate__animated animate__fadeInLeft animate__slow">
            <a href="/">
              <img src={logoWhiteSmileText} alt="sermify-logo" />
            </a>
          </div>
          <div className="button-container wow animate__animated animate__fadeInRight animate__slow">
            <Link to="/login">
              <button className="login-button">Giriş Yap</button>
            </Link>
            <Link to="/register">
              <button className="register-button">Kayıt Ol</button>
            </Link>
          </div>
          <div className="button-container-mobile wow animate__animated animate__fadeInRight animate__slow">
            <Link to="/login">
              <button>
                Giriş Yap <BoxArrowInRight></BoxArrowInRight>
              </button>
            </Link>
          </div>
        </nav>
        <div className="slogan-container">
          <div className="slogan-container-texts wow animate__animated animate__fadeInDown animate__slow">
            <h3>SEVİYENE GÖRE İNGİLİZCE</h3>
            <p>Öğrenmek için</p>
            <p className="smile-text">gülümse!</p>
            <span>
              <div className="slogan-container-long-text">
                Seviyene göre ingilizce testler ve alıştırmalar ile ingilizceni
                geliştir. <br />
                Puan tablosundaki rakiplerin ile yarış!
              </div>
            </span>
            <Link to="/register">
              <button className="join-us-button">
                Sen de aramıza katıl --{'>'}
              </button>
            </Link>
          </div>
          <div className="smile-shape-container wow animate__animated animate__zoomIn animate__slow">
            <img src={smileShape} alt="smile-shape" className="smile-shape" />
          </div>
        </div>
        <div className="laptop-screen wow animate__animated animate__fadeInLeft animate__slow">
          <img src={laptop} alt="laptop" />
        </div>
        <button>
          <div className="mobile-scroll-button">
            <ChevronDown
              onClick={() => {
                document
                  .querySelector('.section-2')
                  .scrollIntoView({ behavior: 'smooth' });
              }}
            ></ChevronDown>
          </div>
        </button>
      </div>
      <div className="section-2">
        <div className="section-2-text wow animate__animated animate__fadeInDown animate__slow">
          <p>Seviyene Göre İngilizce!</p>
          <p>
            Seviyene göre ingilizce testler ve alıştırmalar ile ingilizceni
            geliştir. <br />
            Puan tablosundaki rakiplerin ile yarış!
          </p>
        </div>
        <div className="section-box-container">
          <div className="section-2-box wow animate__animated animate__fadeInRight animate__slow">
            <span>
              <Reception4></Reception4>
            </span>
            <div>
              <span>Seviyene Göre</span>
              <p>
                Video testler ile ingilizcenin gerçek hayatta nasıl
                kullanıldığını keşfet!
              </p>
            </div>
          </div>
          <div className="section-2-box wow animate__animated animate__fadeInRight animate__slow">
            <span>
              <MoonFill></MoonFill>
            </span>
            <div>
              <span>Açık/Koyu Tema</span>
              <p>
                Kullanıcılarımızın rahatlığı için açık ve koyu tema seçenekleri
                sunuyoruz.
              </p>
            </div>
          </div>
          <div className="section-2-box wow animate__animated animate__fadeInRight animate__slow">
            <span>
              <TrophyFill></TrophyFill>
            </span>
            <div>
              <span>Puan Tablosu</span>
              <p>Puan tablosunda rakiplerin ile yarış, kendini test et!</p>
            </div>
          </div>
        </div>
      </div>
      <div className="section-3">
        <div className="smile-shape wow animate__animated animate__fadeInRight animate__slow">
          <img src={smileShapeMidGrey} alt="smile-shape" />
        </div>
        <div className="mobile-screen wow animate__animated animate__fadeInLeft animate__slow">
          <img
            className="mobile-screen-with-text"
            src={mobile}
            alt="mobile-screen"
          />
          <img
            className="mobile-screen-without-text"
            src={mobileWithoutText}
            alt="mobile-screen"
          />
        </div>
      </div>
      <footer>
        <div className="footer-sermify wow animate__animated animate__fadeInLeft animate__slow">
          <a href="/">
            <img src={logoWhiteSmileText} alt="sermify-logo" />
          </a>
        </div>
        <div className="footer-copyright wow animate__animated animate__fadeInUp animate__slow">
          <p>© {new Date().getFullYear()} Sermify | Tüm Hakları Saklıdır</p>
        </div>
        <div className="developers-name wow animate__animated animate__fadeInRight animate__slow">
          Developed by
          <a href="https://www.linkedin.com/in/sema-ermin/" target="_blank">
            Sema E.
          </a>
          and
          <a
            href="https://www.linkedin.com/in/serhatzunluoglu/"
            target="_blank"
          >
            Serhat İ. Z.
          </a>
        </div>
      </footer>
    </div>
  );
}

export default LandingPage;
