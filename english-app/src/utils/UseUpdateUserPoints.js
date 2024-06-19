import { useTheme } from '../context/ThemeContext';

const useUpdateUserPoints = () => {
  const { setUser } = useTheme();

  const updateUserPoints = (newPoints) => {
    setUser((prevUser) => {
      const updatedUser = { ...prevUser, point: newPoints };
      localStorage.setItem('user', JSON.stringify(updatedUser));
      return updatedUser;
    });
  };

  return updateUserPoints;
};

export default useUpdateUserPoints;
