import { useState } from 'react';
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
} from 'react-bootstrap-icons';

export default function Navbar(props) {
  const { toggleTheme, theme } = useTheme();

  const [selectedItem, setSelectedItem] = useState(props?.item);
  const [userProfile, setUserProfile] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);


  const handleItemClick = (item) => {
    setSelectedItem(item);
    if (menuOpen) setMenuOpen(false); // Close menu when an item is clicked
  };

  const userProfileClick = (item) => {
    setUserProfile(item);
  };

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  return (
    <div className={theme}>
      <navbar>
        <div className="sermify-logo">
          <img
            className="sermify-logo-img"
            src="/src/assets/images/svg/logo-red.svg"
            alt="sermify-red-logo"
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
          <button onClick={toggleTheme}>Tema Değiştir</button>
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
        {menuOpen ? <div className="navbar-toggle">X</div> : <div className="navbar-toggle">Y</div>}
        {/* <div className="navbar-toggle">X</div> */}
      </navbar>
    </div>
  );
}
