import React, { useEffect, useState } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import LINEMiniApp from './components/LINEMiniApp';
import liff from '@line/liff';

function App() {
  const [liffObject, setLiffObject] = useState(null);
  const [liffError, setLiffError] = useState(null);

  useEffect(() => {
    liff
      .init({ liffId: process.env.REACT_APP_LIFF_ID })
      .then(() => {
        setLiffObject(liff);
      })
      .catch((error) => {
        setLiffError(error.toString());
      });
  }, []);

  if (liffError) {
    return <div>エラーが発生しました: {liffError}</div>;
  }

  if (!liffObject) {
    return <div>読み込み中...</div>;
  }

  return (
    <Router>
      <Routes>
        <Route path="/" element={<LINEMiniApp liff={liffObject} />} />
      </Routes>
    </Router>
  );
}

export default App; 