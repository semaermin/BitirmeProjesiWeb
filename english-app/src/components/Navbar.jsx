import { useState, useEffect, useRef } from 'react';
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
  PersonFill,
  Person,
  Sun,
  MoonFill,
  ChevronDown,
} from 'react-bootstrap-icons';

import logoWhiteSmileText from '../assets/images/svg/logo-white-smile-text.svg';
import logoRedSmileText from '../assets/images/svg/logo-red-smile-text.svg';

export default function Navbar(props) {
  const { toggleTheme, theme, user } = useTheme();
  const [selectedItem, setSelectedItem] = useState(props?.item);
  const [menuOpen, setMenuOpen] = useState(false);
  const [themeSwitcher, setThemeSwitcher] = useState(false);
  const [dropdownOpen, setDropdownOpen] = useState(false);
  const navigate = useNavigate();
  const navRef = useRef(null);
  const lastItemRef = useRef(null);
  const profilePhotoUrl = user.profile_photo_path
    ? `${import.meta.env.VITE_API_URL}/storage/${user.profile_photo_path}`
    : `${user.profile_photo_url}&size=100&background=random`;

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

  useEffect(() => {
    var rootElement = document.getElementById('root');
    if (rootElement) {
      if (theme === 'light') {
        rootElement.classList.remove('dark');
        rootElement.classList.add('light');
      } else {
        rootElement.classList.remove('light');
        rootElement.classList.add('dark');
      }
    }
  }, [theme]);

  useEffect(() => {
    const handleKeyDown = (event) => {
      if (event.key === 'F1') {
        event.preventDefault();
        handleItemClick('help');
        navigate('/help');
      }
    };

    document.addEventListener('keydown', handleKeyDown);
    return () => {
      document.removeEventListener('keydown', handleKeyDown);
    };
  }, []);

  const handleItemClick = (item) => {
    setSelectedItem(item);
    if (menuOpen) {
      setMenuOpen(false);
    }
  
    if (item === 'logout') {
      var rootElement = document.getElementById('root');
      if (rootElement) {
        if (theme === 'light') {
          rootElement.classList.remove('light');
        } else {
          rootElement.classList.remove('dark');
        }
      }
      handleLogout();
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
      // Backend'deki logout API'sini çağır
      await fetch('/user/logout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          // Gerekli ise auth header ekle
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
        },
        credentials: 'include', // Cookie gönderimini sağlamak için
      });
  
      // LocalStorage'dan token'ları temizle
      localStorage.removeItem('token');
      localStorage.removeItem('user');
  
      // Kullanıcıyı login sayfasına yönlendir
      navigate('/login');
    } catch (error) {
      console.error('Logout error:', error);
    }
  };

  const toggleDropdown = () => {
    setDropdownOpen(!dropdownOpen);
  };

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (navRef.current && !navRef.current.contains(event.target)) {
        setDropdownOpen(false);
      }
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
    };
  }, []);

  return (
    <div className={theme} ref={navRef}>
      <nav className={`${menuOpen ? 'menu-open' : ''}`}>
        <div className="sermify-logo">
          <Link to="/home">
            <img
              className="sermify-logo-img"
              src={menuOpen ? logoWhiteSmileText : logoRedSmileText}
              alt={menuOpen ? 'sermify-red-logo' : 'sermify-white-logo'}
            />
          </Link>
        </div>
        <div className="main-links">
          <ul>
            <Link to="/home">
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
            </Link>
            <Link to="/video">
              <li
                onClick={() => handleItemClick('video')}
                style={{
                  color:
                    selectedItem === 'video'
                      ? 'var(--primary-color)'
                      : 'var(--tertiary-color)',
                }}
              >
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
            </Link>
            <Link to="/exercises">
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
                    fontWeight:
                      selectedItem === 'exercises' ? 'bold' : 'normal',
                  }}
                  title="Alıştırmalar"
                >
                  Alıştırmalar
                </span>
              </li>
            </Link>
            <Link to="/leaderboard">
              <li
                onClick={() => handleItemClick('leaderboard')}
                style={{
                  color:
                    selectedItem === 'leaderboard'
                      ? 'var(--primary-color)'
                      : 'var(--tertiary-color)',
                  fontWeight:
                    selectedItem === 'leaderboard' ? 'bold' : 'normal',
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
                  Puan Tablosu
                </span>
              </li>
            </Link>
          </ul>
        </div>
        <div className="user-profile" onClick={toggleDropdown}>
          <div className="name-point-info">
            <div className="user-fullname">{user?.name}</div>
            <div className="user-point">
              <span>
                <ChevronDown></ChevronDown>
              </span>
              Puan: {user.point}
            </div>
          </div>
          <div className="user-avatar">
            <img src={profilePhotoUrl} alt="user-photo" />
            {dropdownOpen && (
              <div className="dropdown-content">
                <ul>
                  <Link
                    to="/profile"
                    onClick={() => handleItemClick('profile')}
                    data-selected={
                      selectedItem === 'profile' ? 'true' : 'false'
                    }
                  >
                    {selectedItem === 'profile' ? (
                      <PersonFill />
                    ) : (
                      <PersonFill />
                    )}
                    <span
                      style={{
                        fontWeight:
                          selectedItem === 'profile' ? 'bold' : 'normal',
                      }}
                      title="Profilim"
                    >
                      Profilim
                    </span>
                  </Link>
                  <Link
                    to="/help"
                    onClick={() => handleItemClick('help')}
                    data-selected={selectedItem === 'help' ? 'true' : 'false'}
                  >
                    {selectedItem === 'help' ? (
                      <QuestionCircleFill />
                    ) : (
                      <QuestionCircleFill />
                    )}
                    <span
                      style={{
                        fontWeight: selectedItem === 'help' ? 'bold' : 'normal',
                      }}
                      title="Yardım"
                    >
                      Yardım
                    </span>
                  </Link>
                  <Link
                    to="/login"
                    onClick={() => handleItemClick('logout')}
                    data-selected={selectedItem === 'logout' ? 'true' : 'false'}
                  >
                    {selectedItem === 'logout' ? (
                      <ArrowLeftCircleFill />
                    ) : (
                      <ArrowLeftCircleFill />
                    )}
                    <span
                      style={{
                        fontWeight:
                          selectedItem === 'logout' ? 'bold' : 'normal',
                      }}
                      title="Çıkış Yap"
                    >
                      Çıkış Yap
                    </span>
                  </Link>

                  <li
                    id="dropdown-theme-switcher"
                    ref={lastItemRef}
                    data-selected={
                      selectedItem === 'profile' ? 'true' : 'false'
                    }
                  >
                    <div className="theme-switcher" onClick={themeSwitch}>
                      {themeSwitcher ? <MoonFill /> : <Sun />}
                    </div>
                  </li>
                </ul>
              </div>
            )}
          </div>
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
              <li>
                <div className="mobile-user-profile">
                  <div className="mobile-user-avatar" onClick={toggleDropdown}>
                    <img src={profilePhotoUrl} alt="user-photo" />
                  </div>
                  <div className="mobile-user-fullname">{user?.name}</div>
                </div>
              </li>
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
                  <Link to="/help">Yardım</Link>
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
                </div>
              </li>
            </ul>
          </div>
        )}
      </nav>
    </div>
  );
}
