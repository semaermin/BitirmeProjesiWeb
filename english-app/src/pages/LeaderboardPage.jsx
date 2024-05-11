import { useState, useEffect } from 'react';
import Navbar from '../components/navbar';

function LeaderboardPage() {
  const [users, setUsers] = useState([]);

  useEffect(() => {
    fetchUsers();
  }, []);

  async function fetchUsers() {
    try {
      const response = await fetch('http://127.0.0.1:8000/users');
      if (response.ok) {
        const data = await response.json();
        if (data && data.users && Array.isArray(data.users)) {
          // Kullanıcıları level'lerine göre sırala
          const sortedUsers = data.users.sort((a, b) => b.level - a.level);
          setUsers(sortedUsers);
        } else {
          console.error('Invalid user data format');
        }
      } else {
        console.error('Failed to fetch users:', response.statusText);
      }
    } catch (error) {
      console.error('Failed to fetch users:', error);
    }
  }

  return (
    <div>
      <Navbar item="leaderboard"></Navbar>
      <h1>Leaderboard</h1>
      <ul>
        {users.map((user, index) => (
          <li key={user.id}>
            {index + 1}. {user.name} - Level {user.level} - Puan {user.point}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default LeaderboardPage;
