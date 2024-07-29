// Third Party Imports
import { Helmet } from 'react-helmet-async';

import Navbar from '../components/Navbar';
import UserProfile from '../components/UserProfile';
import ProfileSettings from '../components/ProfileSettings';
import { useTheme } from '../context/ThemeContext';
import { useState } from 'react';

function UserProfilePage() {
  const { user } = useTheme();
  const [userName, setUserName] = useState(user.name);

  return (
    <div>
      <Helmet>
        <meta
          name="description"
          content="Profil sayfanızdan hesap bilgilerinizi görüntüleyebilir ve güncelleyebilirsiniz."
        />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="Sermify | Profilim" />
        <meta
          property="og:description"
          content="Profil sayfanızdan hesap bilgilerinizi görüntüleyebilir ve güncelleyebilirsiniz."
        />
        <meta property="og:locale" content="tr_TR" />
        <meta property="og:site_name" content="Sermify" />
        <meta
          property="og:image"
          content="https://www.sermify.com.tr/sermify-seo-background.png"
        />
        <title>
          {userName && userName
            ? `${userName} | Sermify`
            : 'Profilim | Sermify'}
        </title>
      </Helmet>
      <Navbar item="profile"></Navbar>
      <UserProfile></UserProfile>
      <ProfileSettings></ProfileSettings>
    </div>
  );
}

export default UserProfilePage;
