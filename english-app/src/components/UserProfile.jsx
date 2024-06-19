import React from 'react';
import { useTheme } from '../context/ThemeContext';
import '../assets/styles/components/user-profile.scss';
import { LightningChargeFill, TrophyFill } from 'react-bootstrap-icons';

export default function UserProfile() {
  const { user, theme } = useTheme();

  return (
    <div className={theme}>
      <div className="user-profile-container">
        <div className="user-background">
          <div className="user-profile-photo">
            <img
              src={
                user.profile_photo_path
                  ? user.profile_photo_path
                  : user.profile_photo_url
              }
              alt="user-profile-photo"
            />
            <p>{user.name}</p>
          </div>
        </div>
        <div className="user-info">
          <div className="user-point">
            <span>
              <LightningChargeFill></LightningChargeFill> {user.point}
            </span>
            <span>Puan</span>
          </div>
          <div className="user-arrangement">
            <span>
              <TrophyFill></TrophyFill> {user.rank}
            </span>
            <span>Sıralama</span>
          </div>
        </div>
      </div>
    </div>
  );
}
