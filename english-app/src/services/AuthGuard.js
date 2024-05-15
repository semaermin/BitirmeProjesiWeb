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
  ];

  useEffect(() => {
    checkUserLoggedIn();
  }, []);

  function checkUserLoggedIn() {
    const token = localStorage.getItem('token');
    if (
      pagesWithAuthGuard.includes(location.pathname) &&
      localStorage.getItem('token')
    ) {
      // If the user is on one of the pages in the pagesWithAuthGuard array and there is a token, assign the user to the home page
      navigate('/home');
    } else if (!token && !pagesWithAuthGuard.includes(location.pathname)) {
      navigate('/login');
      // If there is no token and the user is not on one of the pages in the pagesWithAuthGuard array, assign the user to the login page
    }
  }

  return children;
}

export default AuthGuard;
