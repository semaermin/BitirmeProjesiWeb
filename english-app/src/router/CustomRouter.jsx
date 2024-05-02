import LoginPage from '../pages/LoginPage.jsx';
import RegisterPage from '../pages/RegisterPage.jsx';
import HomePage from '../pages/HomePage.jsx';
import UserProfilePage from '../pages/UserProfilePage.jsx';
import VideoPage from '../pages/VideoPage.jsx';
import ExercisesPage from '../pages/ExercisesPage.jsx';
import LeaderboardPage from './../pages/LeaderboardPage.jsx';
import ForgotPasswordPage from '../pages/ForgotPasswordPage.jsx';

export const CustomRouter = [
  { path: '/login', element: <LoginPage /> },
  { path: '/register', element: <RegisterPage /> },
  { path: '/home', element: <HomePage /> },
  { path: '/profile', element: <UserProfilePage /> },
  { path: '/video', element: <VideoPage /> },
  { path: '/exercises', element: <ExercisesPage /> },
  { path: '/leaderboard', element: <LeaderboardPage /> },
  { path: '/forgot-password', element: <ForgotPasswordPage /> },
];
