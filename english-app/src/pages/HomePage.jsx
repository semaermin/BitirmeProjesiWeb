import React, { useState, useEffect } from 'react';
import axios from 'axios';

function HomePage() {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    // Kullanıcıları getiren API'ye istek yap
    axios.get('http://127.0.0.1:8000/api/users')
      .then(response => {
        console.log(response.data); 
        // API'den gelen kullanıcıları state'e kaydet
        setUsers(response.data);
      })
      .catch(error => {
        console.error('Kullanıcıları getirirken bir hata oluştu:', error);
      });
  }, []); // Boş bağımlılık dizisi, sadece bir kere yükleme yapılmasını sağlar

  return (
    <div>
      <h1>Kullanıcılar</h1>
      <ul>
        {users.length > 0 ? (
          users.map(user => (
            <li key={user.id}>
              {user.name} - {user.email}
            </li>
          ))
        ) : (
          <li>Henüz kullanıcı yok</li>
        )}
      </ul>
    </div>
  );
}

export default HomePage;
