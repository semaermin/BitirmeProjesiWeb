// React Imports
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import { CustomRouter } from './router/CustomRouter';
import AuthGuard from './services/AuthGuard';

// Styles
import './App.css';

function App() {
  return (
    <>
      <Router>
        <Routes>
          {CustomRouter.map((item, index) => {
            return <Route path={item.path} key={index} element={<AuthGuard>{item.element}</AuthGuard>} />;
          })}
        </Routes>
      </Router>
    </>
  );
}

export default App;
