import {
  BoxArrowInRight,
  ChevronDown,
  PeopleFill,
  Reception4,
  TrophyFill,
} from 'react-bootstrap-icons';
import '../assets/styles/landing-page.scss';
import { Link } from 'react-router-dom';

function LandingPage() {
  return (
    <div>
      <div className="section-1">
        <nav>
          <div className="sermify-logo">
            <Link to="/">
              <img
                src="src/assets/images/svg/logo-white-smile-text.svg"
                alt="sermify-logo"
              />
            </Link>
          </div>
          <div className="button-container">
            <Link to="/login">
              <button className="login-button">Giriş Yap</button>
            </Link>
            <Link to="/register">
              <button className="register-button">Kayıt Ol</button>
            </Link>
          </div>
          <div className="button-container-mobile">
            <Link to="/login">
              <button>
                Giriş Yap <BoxArrowInRight></BoxArrowInRight>
              </button>
            </Link>
          </div>
        </nav>
        <div className="slogan-container">
          <div className="slogan-container-texts">
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
          <div className="smile-shape-container">
            <img
              src="src/assets/images/svg/smile-shape.svg"
              alt="smile-shape"
              className="smile-shape"
            />
          </div>
        </div>
        <div className="laptop-screen">
          <img src="src/assets/images/laptop.png" alt="laptop" />
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
        <div className="section-2-text">
          <p>Seviyene Göre İngilizce!</p>
          <p>
            Seviyene göre ingilizce testler ve alıştırmalar ile ingilizceni
            geliştir. <br />
            Puan tablosundaki rakiplerin ile yarış!
          </p>
        </div>
        <div className="section-box-container">
          <div className="section-2-box">
            <span>
              <PeopleFill></PeopleFill>
            </span>
            <div>
              <span>Seviyene Göre</span>
              <p>
                Video testler ile ingilizcenin gerçek hayatta nasıl
                kullanıldığını keşfet!
              </p>
            </div>
          </div>
          <div className="section-2-box">
            <span>
              <Reception4></Reception4>
            </span>
            <div>
              <span>Hızlı Altyapı</span>
              <p>
                Kullanıcı deneyimini hızlı altyapımızla en üst seviyeye
                getiriyoruz.
              </p>
            </div>
          </div>
          <div className="section-2-box">
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
        <div className="smile-shape">
          <img
            src="/src/assets/images/svg/smile-shape-mid-grey.svg"
            alt="smile-shape"
          />
        </div>
        <div className="mobile-screen">
          <img
            className="mobile-screen-with-text"
            src="/src/assets/images/mobile.png"
            alt="mobile-screen"
          />
          <img
            className="mobile-screen-without-text"
            src="/src/assets/images/mobile-without-text.png"
            alt="mobile-screen"
          />
        </div>
      </div>
      <footer>
        <div className="footer-sermify">
          <img
            src="/src/assets/images/svg/logo-white-smile-text.svg"
            alt="sermify-logo"
          />
        </div>
        <div className="footer-copyright">
          <p>{new Date().getFullYear()} Sermify | Tüm hakları saklıdır.</p>
        </div>
        <div className="developers-name">
          Developed by
          <a href="https://www.linkedin.com/in/sema-ermin/" target="_blank">
            Sema E.
          </a>
          and
          <a
            href="https://www.linkedin.com/in/serhatzunluoglu/"
            target="_blank"
          >
            Serhat Z.
          </a>
        </div>
      </footer>
    </div>
  );
}

export default LandingPage;
