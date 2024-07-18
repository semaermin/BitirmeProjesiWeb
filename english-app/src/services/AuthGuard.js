import { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

function AuthGuard({ children }) {
  const navigate = useNavigate();
  const location = useLocation();

  // pages outside the application
  const pagesWithAuthGuard = [
    '/',
    '/login',
    '/register',
    '/forgot-password',
    '/sign-in',
    '/auth/google',
    '/reset-password',
  ];

  useEffect(() => {
    checkUserLoggedIn();
  }, []);

  function checkUserLoggedIn() {
    const token = localStorage.getItem('token');
    const path = location.pathname;

    const isResetPasswordWithToken = path.startsWith('/reset-password/');

    if (token && pagesWithAuthGuard.includes(path)) {
      navigate('/home');
    } else if (path === '/reset-password') {
      navigate('/');
    }
  }

  return children;
}

export default AuthGuard;
