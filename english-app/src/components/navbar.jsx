import { useState, useEffect } from 'react';
import { useTheme } from '../context/ThemeContext';
import { Link } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';
import '../assets/styles/components/navbar.scss';
import {
  House,
  HouseFill,
  CameraReels,
  CameraReelsFill,
  RocketTakeoff,
  RocketTakeoffFill,
  Trophy,
  TrophyFill,
  List,
  XLg,
  ArrowLeftCircleFill,
  ArrowLeftCircle,
  QuestionCircleFill,
  QuestionCircle,
  GearFill,
  Gear,
  PersonFill,
  Person,
  Sun,
  MoonFill,
} from 'react-bootstrap-icons';

export default function Navbar(props) {
  const { toggleTheme, theme } = useTheme();
  const [selectedItem, setSelectedItem] = useState(props?.item);
  const [menuOpen, setMenuOpen] = useState(false);
  const [themeSwitcher, setThemeSwitcher] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const handleResize = () => {
      const screenWidth = window.innerWidth;
      if (screenWidth > 1024) {
        setMenuOpen(false);
      } else {
        setMenuOpen(false);
      }
    };
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const handleItemClick = (item) => {
    setSelectedItem(item);
    if (menuOpen) {
      setMenuOpen(false);
    }

    if (item === 'logout') {
      handleLogout();
    } else {
      // Diğer menü öğeleri için gerekli işlemleri yapabilirsiniz
    }
  };

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  const themeSwitch = () => {
    setThemeSwitcher(!themeSwitcher);
    toggleTheme();
  };

  const handleLogout = async () => {
    try {
      // Tokeni localStorage'dan temizle
      localStorage.removeItem('token');
      // Çıkış başarılıysa, kullanıcıyı giriş sayfasına yönlendir
      navigate('/login');
    } catch (error) {
      console.error('Çıkış hatası:', error); // Hata oluşursa konsola yazdır
    }
  };

  return (
    <div className={theme}>
      <navbar className={`${menuOpen ? 'menu-open' : ''}`}>
        <div className="sermify-logo">
          <img
            className="sermify-logo-img"
            src={
              menuOpen
                ? '/src/assets/images/svg/logo-white.svg'
                : '/src/assets/images/svg/logo-red.svg'
            }
            alt={menuOpen ? 'sermify-red-logo' : 'sermify-white-logo'}
          />
        </div>
        <div className="main-links">
          <ul>
            <li
              onClick={() => handleItemClick('home')}
              style={{
                color:
                  selectedItem === 'home'
                    ? 'var(--primary-color)'
                    : 'var(--tertiary-color)',
              }}
            >
              {selectedItem === 'home' ? <HouseFill /> : <House />}
              <span
                style={{
                  fontWeight: selectedItem === 'home' ? 'bold' : 'normal',
                }}
                title="Ana Sayfa"
              >
                <Link to="/home">Ana Sayfa</Link>
              </span>
            </li>
            <li
              onClick={() => handleItemClick('video')}
              style={{
                color:
                  selectedItem === 'video'
                    ? 'var(--primary-color)'
                    : 'var(--tertiary-color)',
              }}
            >
              {selectedItem === 'video' ? <CameraReelsFill /> : <CameraReels />}
              <span
                style={{
                  fontWeight: selectedItem === 'video' ? 'bold' : 'normal',
                }}
                title="Video"
              >
                <Link to="/video">Video</Link>
              </span>
            </li>
            <li
              onClick={() => handleItemClick('exercises')}
              style={{
                color:
                  selectedItem === 'exercises'
                    ? 'var(--primary-color)'
                    : 'var(--tertiary-color)',
              }}
            >
              {selectedItem === 'exercises' ? (
                <RocketTakeoffFill />
              ) : (
                <RocketTakeoff />
              )}
              <span
                style={{
                  fontWeight: selectedItem === 'exercises' ? 'bold' : 'normal',
                }}
                title="Alıştırmalar"
              >
                <Link to="/exercises">Alıştırmalar</Link>
              </span>
            </li>
            <li
              onClick={() => handleItemClick('leaderboard')}
              style={{
                color:
                  selectedItem === 'leaderboard'
                    ? 'var(--primary-color)'
                    : 'var(--tertiary-color)',
                fontWeight: selectedItem === 'leaderboard' ? 'bold' : 'normal',
              }}
            >
              {selectedItem === 'leaderboard' ? <TrophyFill /> : <Trophy />}
              <span
                style={{
                  fontWeight:
                    selectedItem === 'leaderboard' ? 'bold' : 'normal',
                }}
                title="Puan Tablosu"
              >
                <Link to="/leaderboard">Puan Tablosu</Link>
              </span>
            </li>
          </ul>
        </div>
        <div className="user-profile">
          <div className="theme-switcher" onClick={themeSwitch}>
            <img
              src={
                themeSwitcher
                  ? '/src/assets/images/svg/crescent.svg'
                  : '/src/assets/images/svg/sun.svg'
              }
              alt="sun-icon"
            />
          </div>
          <div className="user-fullname">Muzaffer Enes Yıldırım</div>
          <img
            onClick={() => userProfileClick('user-profile')}
            src="/src/assets/images/user-avatar.png"
            alt="user-photo"
          />
        </div>
        <div className="navbar-toggle" onClick={toggleMenu}>
          {menuOpen ? (
            <XLg color={'white'} width={'28px'} height={'28px'} />
          ) : (
            <List
              color={theme === 'light' ? 'black' : 'white'}
              width={'28px'}
              height={'28px'}
            />
          )}
        </div>
        {menuOpen && (
          <div className="mobile-menu-items">
            <ul>
              <li onClick={() => handleItemClick('home')}>
                {selectedItem === 'home' ? <HouseFill /> : <House />}
                <span
                  style={{
                    fontWeight: selectedItem === 'home' ? 'bold' : 'normal',
                  }}
                  title="Ana Sayfa"
                >
                  <Link to="/home">Ana Sayfa</Link>
                </span>
              </li>
              <li onClick={() => handleItemClick('video')}>
                {selectedItem === 'video' ? (
                  <CameraReelsFill />
                ) : (
                  <CameraReels />
                )}
                <span
                  style={{
                    fontWeight: selectedItem === 'video' ? 'bold' : 'normal',
                  }}
                  title="Video"
                >
                  <Link to="/video">Video</Link>
                </span>
              </li>
              <li onClick={() => handleItemClick('exercises')}>
                {selectedItem === 'exercises' ? (
                  <RocketTakeoffFill />
                ) : (
                  <RocketTakeoff />
                )}
                <span
                  style={{
                    fontWeight:
                      selectedItem === 'exercises' ? 'bold' : 'normal',
                  }}
                  title="Alıştırmalar"
                >
                  <Link to="/exercises">Alıştırmalar</Link>
                </span>
              </li>
              <li onClick={() => handleItemClick('leaderboard')}>
                {selectedItem === 'leaderboard' ? <TrophyFill /> : <Trophy />}
                <span
                  style={{
                    fontWeight:
                      selectedItem === 'leaderboard' ? 'bold' : 'normal',
                  }}
                  title="Puan Tablosu"
                >
                  <Link to="/leaderboard">Puan Tablosu</Link>
                </span>
              </li>
              <li onClick={() => handleItemClick('profile')}>
                {selectedItem === 'profile' ? <PersonFill /> : <Person />}
                <span
                  style={{
                    fontWeight: selectedItem === 'profile' ? 'bold' : 'normal',
                  }}
                  title="Profilim"
                >
                  <Link to="/profile">Profilim</Link>
                </span>
              </li>
              <li onClick={() => handleItemClick('settings')}>
                {selectedItem === 'settings' ? <GearFill /> : <Gear />}
                <span
                  style={{
                    fontWeight: selectedItem === 'settings' ? 'bold' : 'normal',
                  }}
                  title="Ayarlar"
                >
                  Ayarlar
                </span>
              </li>
              <li onClick={() => handleItemClick('help')}>
                {selectedItem === 'help' ? (
                  <QuestionCircleFill />
                ) : (
                  <QuestionCircle />
                )}
                <span
                  style={{
                    fontWeight: selectedItem === 'help' ? 'bold' : 'normal',
                  }}
                  title="Yardım"
                >
                  Yardım
                </span>
              </li>
              <li onClick={() => handleItemClick('logout')}>
                {selectedItem === 'logout' ? (
                  <ArrowLeftCircleFill />
                ) : (
                  <ArrowLeftCircle />
                )}
                <span
                  style={{
                    fontWeight: selectedItem === 'logout' ? 'bold' : 'normal',
                  }}
                  title="Çıkış Yap"
                >
                  Çıkış Yap
                </span>
              </li>
              <li>
                <div className="theme-switcher" onClick={themeSwitch}>
                  {themeSwitcher ? <MoonFill /> : <Sun />}
                  <span>Tema</span>
                  {/* {themeSwitcher ? (
                    <span style={{ color: 'black' }}>Koyu Tema</span>
                  ) : (
                    <span style={{ color: 'white' }}>Açık Tema</span>
                  )} */}
                </div>
              </li>
            </ul>
          </div>
        )}
      </navbar>
    </div>
  );
}
