import React from 'react';
import '../assets/styles/components/user-profile.scss';

export default function UserProfile() {
  return (
    <div>
      <div className="user-profile-container">
        <div className="user-background"></div>
        <div className="user-info">
          <div className="user-point">kullanıcı puanı</div>
          <div className="user-arrangement"></div>
        </div>
      </div>
    </div>
  );
}
