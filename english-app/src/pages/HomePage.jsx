import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import Navbar from '../components/navbar';
import axios from 'axios';

function HomePage() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const navigate = useNavigate(); // useNavigate kullanımı

  // Kullanıcı oturumunu kontrol etmek için useEffect kullanma
  useEffect(() => {
    // Oturum kontrolü yapılacak yer
    checkUserLoggedIn();
  }, []); // useEffect'i bağımsız hale getir

  // Kullanıcı oturumu kontrol fonksiyonu (örneğin: token kontrolü)
  function checkUserLoggedIn() {
    axios.get('http://127.0.0.1:8000/api/userLoggedIn')
      .then(response => {
        const isLoggedIn = response.data.isLoggedIn;
        console.log("Kullanıcı oturumu:", isLoggedIn);
        // Oturum durumuna göre işlem yap
      })
      .catch(error => {
        console.error("Oturum durumu sorgulanırken hata oluştu:", error);
      });
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
