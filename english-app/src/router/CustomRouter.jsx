import LoginPage from '../pages/LoginPage.jsx';
import RegisterPage from '../pages/RegisterPage.jsx';
import HomePage from '../pages/HomePage.jsx';
import SignIn from '../pages/SignIn.jsx';
import GoogleCallback from '../pages/GoogleCallback';
import UserProfilePage from '../pages/UserProfilePage.jsx';
import VideoPage from '../pages/VideoPage.jsx';
import ExercisesPage from '../pages/ExercisesPage.jsx';
import LeaderboardPage from './../pages/LeaderboardPage.jsx';
import ForgotPasswordPage from '../pages/ForgotPasswordPage.jsx';
import NotFoundPage from '../pages/NotFoundPage.jsx';
import HelpPage from '../pages/HelpPage.jsx';

export const CustomRouter = [
  { path: '/login', element: <LoginPage /> },
  { path: '/register', element: <RegisterPage /> },
  { path: '/home', element: <HomePage /> },
  { path: '/sign-in', element: <SignIn /> },
  { path: '/auth/google', element: <GoogleCallback /> },
  { path: '/profile', element: <UserProfilePage /> },
  { path: '/video', element: <VideoPage /> },
  { path: '/exercises', element: <ExercisesPage /> },
  { path: '/leaderboard', element: <LeaderboardPage /> },
  { path: '/forgot-password', element: <ForgotPasswordPage /> },
  { path: '/help', element: <HelpPage /> },
  { path: '*', element: <NotFoundPage /> },
  { path: '/exercises/:slug', element: <ExercisesPage /> },
];
