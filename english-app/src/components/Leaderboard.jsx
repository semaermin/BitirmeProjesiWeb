import { useTheme } from '../context/ThemeContext';
import { useState, useEffect } from 'react';
import '../assets/styles/components/leaderboard.scss';
import { ChevronLeft, ChevronRight, TrophyFill } from 'react-bootstrap-icons';

export default function Leaderboard({ recordsPerPage = 10 }) {
  const { theme } = useTheme();
  const [data, setData] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  // const recordsPerPage = 10;
  const lastIndex = currentPage * recordsPerPage;
  const firstIndex = lastIndex - recordsPerPage;
  const records = data.slice(firstIndex, lastIndex);
  const npage = Math.ceil(data.length / recordsPerPage);
  const showPages = 5;

  const offset = currentPage - Math.floor(showPages / 2);
  let start = offset;
  if (start < 1) {
    start = 1;
  }
  let end = start + showPages - 1;
  if (end > npage) {
    end = npage;
    start = end - showPages + 1;
  }
  const numbers = Array.from(
    { length: end - start + 1 },
    (_, i) => i + start
  ).filter((num) => num > 0);

  useEffect(() => {
    fetchUsers();
  }, []);

  async function fetchUsers() {
    try {
      const response = await fetch(`${import.meta.env.VITE_API_URL}/users`);
      if (response.ok) {
        const Data = await response.json();
        if (Data && Data.users && Array.isArray(Data.users)) {
          setData(Data.users);
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

  function prePage() {
    if (currentPage !== 1) {
      setCurrentPage(currentPage - 1);
    }
  }
  function changeCPage(id) {
    if (id !== currentPage) {
      setCurrentPage(id);
    }
  }
  function nextPage() {
    if (currentPage !== npage) {
      setCurrentPage(currentPage + 1);
    }
  }

  return (
    <div id="leaderboard" className={theme}>
      <div className="leaderboard">
        <h3 className="leaderboard-title">
          <TrophyFill /> Puan Tablosu
        </h3>
        <div className="leaderboard-infos">
          <span>
            <p>Sıra</p>
            <p>Ad Soyad</p>
          </span>
          <span>
            <p>Puan</p>
            <p>Seviye</p>
          </span>
        </div>
        <ul>
          {records.map((user, index) => (
            <li
              key={firstIndex + index}
              className={`leaderboard-user ${
                firstIndex + index < 3 ? `place-${index + 1}` : ''
              }`}
            >
              <div className="leaderboard-user-left">
                <div className="center-content">{firstIndex + index + 1}.</div>
                <div>{user.name}</div>
              </div>
              <div className="leaderboard-user-right">
                <div className="center-content">{user.point}</div>
                <div className="center-content">{user.level}</div>
              </div>
            </li>
          ))}
        </ul>
        <div className="navigation">
          <ul className="pagination">
            <li className="page-item">
              <button className="page-link" onClick={prePage}>
                <ChevronLeft />
              </button>
            </li>
            {numbers.map((n, i) => (
              <li
                className={`page-item ${currentPage === n ? 'active' : ''}`}
                key={i}
              >
                <button className="page-items" onClick={() => changeCPage(n)}>
                  {n}
                </button>
              </li>
            ))}
            <li className="page-item">
              <button className="page-link" onClick={nextPage}>
                <ChevronRight />
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>
  );
}
