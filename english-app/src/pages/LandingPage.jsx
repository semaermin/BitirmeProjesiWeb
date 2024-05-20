import '../assets/styles/landing-page.scss';
import { Link } from 'react-router-dom';

function LandingPage() {
  return (
    <div>
      <div className="section-1">
        <nav>
          <div className="sermify-logo">
            <img src="src/assets/images/svg/logo-white-smile-text.svg" alt="" />
          </div>
          <div className="button-container">
            <Link to="/login">
              <button className="login-button">Giriş Yap</button>
            </Link>
            <Link to="/register">
              <button className="register-button">Kayıt Ol</button>
            </Link>
          </div>
        </nav>
        <div className="slogan-container">
          <div>
            <h3>SEVİYENE GÖRE İNGİLİZCE</h3>
            <p>Öğrenmek için</p>
            <p className="smile-text">gülümse!</p>
            <span>
              <div>
                Seviyene göre ingilizce testler ve alıştırmalar ile ingilizceni
                geliştir. <br />
                Puan tablosundaki rakiplerin ile yarış!
              </div>
            </span>
            <button className="join-us-button">
              Sen de aramıza katıl --{'>'}
            </button>
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
          <div className="section-2-box"></div>
          <div className="section-2-box"></div>
          <div className="section-2-box"></div>
        </div>
      </div>
      <div className="section-3">3.bölüm</div>
    </div>
  );
}

export default LandingPage;
