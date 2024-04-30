import { useState, useEffect } from 'react';
import { useTheme } from '../context/ThemeContext';
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
} from 'react-bootstrap-icons';

export default function Navbar(props) {
  const { toggleTheme, theme } = useTheme();

  const [selectedItem, setSelectedItem] = useState(props?.item);
  const [userProfile, setUserProfile] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [themeSwitcher, setThemeSwitcher] = useState(false);

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
  };

  const userProfileClick = (item) => {
    setUserProfile(item);
  };

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  const themeSwitch = () => {
    setThemeSwitcher(!themeSwitcher);
    toggleTheme();
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
                Ana Sayfa
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
                Video
              </span>
            </li>
            <li
              onClick={() => handleItemClick('exercise')}
              style={{
                color:
                  selectedItem === 'exercise'
                    ? 'var(--primary-color)'
                    : 'var(--tertiary-color)',
              }}
            >
              {selectedItem === 'exercise' ? (
                <RocketTakeoffFill />
              ) : (
                <RocketTakeoff />
              )}
              <span
                style={{
                  fontWeight: selectedItem === 'exercise' ? 'bold' : 'normal',
                }}
                title="Alıştırmalar"
              >
                Alıştırmalar
              </span>
            </li>
            <li
              onClick={() => handleItemClick('leader-board')}
              style={{
                color:
                  selectedItem === 'leader-board'
                    ? 'var(--primary-color)'
                    : 'var(--tertiary-color)',
                fontWeight: selectedItem === 'leader-board' ? 'bold' : 'normal',
              }}
            >
              {selectedItem === 'leader-board' ? <TrophyFill /> : <Trophy />}
              <span
                style={{
                  fontWeight:
                    selectedItem === 'leader-board' ? 'bold' : 'normal',
                }}
                title="Puan Tablosu"
              >
                Puan Tablosu
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
          </div>{' '}
          <div className="user-fullname">Muzaffer Enes Yıldırım</div>
          <img
            onClick={() => userProfileClick('user-profile')}
            style={{
              border: userProfile === 'user-profile' ? '2px solid red' : 'none',
            }}
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
                  Video
                </span>
              </li>
              <li onClick={() => handleItemClick('exercise')}>
                {selectedItem === 'exercise' ? (
                  <RocketTakeoffFill />
                ) : (
                  <RocketTakeoff />
                )}
                <span
                  style={{
                    fontWeight: selectedItem === 'exercise' ? 'bold' : 'normal',
                  }}
                  title="Alıştırmalar"
                >
                  Alıştırmalar
                </span>
              </li>
              <li onClick={() => handleItemClick('leader-board')}>
                {selectedItem === 'leader-board' ? <TrophyFill /> : <Trophy />}
                <span
                  style={{
                    fontWeight:
                      selectedItem === 'leader-board' ? 'bold' : 'normal',
                  }}
                  title="Puan Tablosu"
                >
                  Puan Tablosu
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
                  Profilim
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
                  <img
                    src={
                      themeSwitcher
                        ? '/src/assets/images/svg/crescent.svg'
                        : '/src/assets/images/svg/sun.svg'
                    }
                    alt="sun-icon"
                  />
                  {themeSwitcher ? (
                    <span>Koyu Tema</span>
                  ) : (
                    <span>Açık Tema</span>
                  )}
                </div>
              </li>
            </ul>
          </div>
        )}
      </navbar>
    </div>
  );
}
