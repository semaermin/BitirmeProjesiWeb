import { useState, useEffect } from 'react';

function HomePage() {
  const [users] = useState([]);

  useEffect(() => {
    fetch('http://127.0.0.1:8000/users')
      .then((response) => response.json())
      .then((data) => console.log(data))
      .catch((error) =>
        console.error("API'dan veri çekilirken hata oluştu:", error)
      );
  }, []);

  return (
    <div>
      <ul>
        {users.map((user) => (
          <li key={user.id}>
            {user.name} - {user.email}
            <img src={user.profile_photo_url} alt="Profile" />
          </li>
        ))}
      </ul>
    </div>
  );
}

export default HomePage;
