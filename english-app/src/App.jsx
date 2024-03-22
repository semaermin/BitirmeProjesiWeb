// React Imports
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import { CustomRouter } from './router/CustomRouter';

// Styles
import './App.css';

function App() {
  
  return (
    <>
      <Router>
        <Routes>
          {CustomRouter.map((item, index) => {
            return <Route key={index} {...item} />;
          })}
        </Routes>
      </Router>
    </>
  );
}

export default App;
