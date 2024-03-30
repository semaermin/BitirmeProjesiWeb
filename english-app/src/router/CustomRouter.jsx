import LoginPage from '../pages/LoginPage.jsx';
import RegisterPage from '../pages/RegisterPage.jsx';
import HomePage from '../pages/HomePage.jsx';
import SignIn from '../pages/SignIn.jsx';

export const CustomRouter = [
  { path: '/', element: <LoginPage /> },
  { path: '/register', element: <RegisterPage /> },
  { path: '/home', element: <HomePage /> },
  { path: '/sign-in', element: <SignIn /> },

];
