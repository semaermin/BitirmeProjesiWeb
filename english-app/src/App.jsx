// Third Party Imports
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import { CustomRouter } from './router/CustomRouter';
import AuthGuard from './services/AuthGuard';
import { HelmetProvider } from 'react-helmet-async';

// Styles
import './App.css';
import 'animate.css/animate.min.css';

function App() {
  return (
    <HelmetProvider>
      <Router>
        <Routes>
          {CustomRouter.map((item, index) => {
            return (
              <Route
                path={item.path}
                key={index}
                element={<AuthGuard>{item.element}</AuthGuard>}
              />
            );
          })}
        </Routes>
      </Router>
    </HelmetProvider>
  );
}

export default App;
