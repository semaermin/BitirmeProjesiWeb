import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import '../assets/styles/components/user-profile.scss';
import {
  LightningChargeFill,
  TrophyFill,
  Reception4,
} from 'react-bootstrap-icons';
import { RotatingLines } from 'react-loader-spinner';

export default function UserProfile() {
  const { theme, user } = useTheme();
  const [userData, setUserData] = useState(null);
  const style = getComputedStyle(document.body);
  const customColor = style.getPropertyValue('--primary-color');

  useEffect(() => {
    fetchUserData(user.uuid);
  }, [user.uuid]);

  const fetchUserData = async (uuid) => {
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_API_URL}/api/user/${uuid}`
      );
      setUserData(response.data.user);
      console.log(localStorage,response);
    } catch (error) {
      console.error('Kullanıcı bilgileri alınırken hata oluştu:', error);
    }
  };

  useEffect(() => {
    const handleClick = () => {
      const fileUploadElement = document.getElementById('file-upload');
      if (fileUploadElement) {
        fileUploadElement.click();
      }
    };

    const userProfilePhoto = document.getElementById('user-profile-photo');
    if (userProfilePhoto) {
      userProfilePhoto.addEventListener('click', handleClick);
    }

    return () => {
      if (userProfilePhoto) {
        userProfilePhoto.removeEventListener('click', handleClick);
      }
    };
  }, []);

  if (!userData) {
    return (
      <div className={theme}>
        <div className="container-loading">
          <RotatingLines
            visible={true}
            height="36"
            width="36"
            strokeWidth="5"
            animationDuration="0.75"
            ariaLabel="rotating-lines-loading"
          />
        </div>
      </div>
    );
  }

  const profilePhotoUrl = userData.profile_photo_path
    ? `${import.meta.env.VITE_API_URL}/storage/${userData.profile_photo_path}`
    : `${userData.profile_photo_url}&size=100&background=random`;

  return (
    <div className={theme}>
      <div className="user-profile-container">
        <div className="user-background">
          <div className="user-profile-photo" id="user-profile-photo">
            <img src={profilePhotoUrl} alt="user-profile-photo" />
            <p>{userData.name}</p>
          </div>
        </div>
        <div className="user-info">
          <div className="user-point">
            <span>
              <LightningChargeFill /> {userData.point}
            </span>
            <span>Puan</span>
          </div>
          <div className="user-arrangement">
            <span>
              <TrophyFill /> {userData.rank}
            </span>
            <span>Sıralama</span>
          </div>
          <div className="user-level">
            <span>
              <Reception4></Reception4>
              {userData.level}
            </span>
            <span>Seviye</span>
          </div>
        </div>
      </div>
    </div>
  );
}
