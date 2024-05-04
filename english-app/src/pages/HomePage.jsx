import { useState, useEffect } from 'react';
import Navbar from '../components/navbar';

function HomePage() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    // Sayfa yüklendiğinde oturum durumunu kontrol et
    checkUserLoggedIn();
  }, []);

  function checkUserLoggedIn() {
    // Token'i localStorage'da sakla
    const token = localStorage.getItem('token');
    if (token) {
      // Eğer token varsa, kullanıcı oturumu açık demektir
      setIsLoggedIn(true);
    } else {
      setIsLoggedIn(false);
    }
  }

  return (
    <div>
      <Navbar item="home"></Navbar>
      {/* <ul>
        {users.map((user) => (
          <li key={user.id}>
            {user.name} - {user.email}
            <img src={user.profile_photo_url} alt="Profile" />
          </li>
        ))}
      </ul> */}
    </div>
  );
}

export default HomePage;
