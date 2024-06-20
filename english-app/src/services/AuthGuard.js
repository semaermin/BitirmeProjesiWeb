import { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

function AuthGuard({ children }) {
  const navigate = useNavigate();
  const location = useLocation();
  const pagesWithAuthGuard = [
    '/',
    '/login',
    '/register',
    '/forgot-password',
    '/sign-in',
    '/auth/google',
    '/reset-password',
    '/auth/google',
    '/sign-in'
  ];

  useEffect(() => {
    checkUserLoggedIn();
  }, []);

  function checkUserLoggedIn() {
    const token = localStorage.getItem('token');
    if (token && pagesWithAuthGuard.includes(location.pathname)) {
      navigate('/home');
    } else if (!token && !pagesWithAuthGuard.includes(location.pathname)) {
      navigate('/login');
    }
  }

  return children;
}

export default AuthGuard;
