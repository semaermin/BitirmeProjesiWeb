import { createContext, useContext, useState } from 'react';

const ThemeContext = createContext();

// eslint-disable-next-line react-refresh/only-export-components
export const useTheme = () => useContext(ThemeContext);

export const ThemeProvider = ({ children }) => {
  const [theme, setTheme] = useState(localStorage.getItem('theme') || 'light');
  const [user, setUser] = useState(JSON.parse(localStorage.getItem('user')));

  const toggleTheme = () => {
    setTheme((current) => {
      const newTheme = current === 'light' ? 'dark' : 'light';
      localStorage.setItem('theme', newTheme);
      return newTheme;
    });
  };

  // const updateUser = (newUserData) => {
  //   return setUser(newUserData);
  // };

  return (
    <ThemeContext.Provider value={{ theme, toggleTheme, user, setUser }}>
      {children}
    </ThemeContext.Provider>
  );
};
