// import { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import Navbar from '../components/navbar';

function HomePage() {
  // const [users] = useState([]);
  const { home } = useParams();
  console.log(home);
  // useEffect(() => {
  //   fetch('http://127.0.0.1:8000/users')
  //     .then((response) => response.json())
  //     .then((data) => console.log(data))
  //     .catch((error) =>
  //       console.error("API'dan veri çekilirken hata oluştu:", error)
  //     );
  // }, []);

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
