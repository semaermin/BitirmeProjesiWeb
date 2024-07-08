// src/GoogleCallback.js

import React, { useState, useEffect } from 'react';
import { useLocation } from 'react-router-dom';

function GoogleCallback() {
  const [loading, setLoading] = useState(true);
  const [data, setData] = useState({});
  const [user, setUser] = useState(null);
  const location = useLocation();

  // useEffect(() => {
  //   fetch(`${import.meta.env.VITE_API_URL}/api/auth/callback${location.search}`, {
  //     headers: {
  //       'Content-Type': 'application/json',
  //       Accept: 'application/json',
  //     },
  //   })
  //     .then((response) => {
  //       return response.json();
  //     })
  //     .then((data) => {
  //       setLoading(false);
  //       setData(data);
  //       console.log(data);
  //     });
  // }, []);

  // function fetchUserData() {
  //   fetch(`${import.meta.env.VITE_API_URL}/api/user`, {
  //     headers: {
  //       'Content-Type': 'application/json',
  //       Accept: 'application/json',
  //       Authorization: 'Bearer ' + data.access_token,
  //     },
  //   })
  //     .then((response) => {
  //       return response.json();
  //     })
  //     .then((data) => {
  //       setUser(data);
  //       console.log(data);
  //     });
  // }

  if (loading) {
    return <DisplayLoading />;
  } else {
    if (user != null) {
      return <DisplayData data={user} />;
    } else {
      return (
        <div>
          <DisplayData data={data} />
          <div style={{ marginTop: 10 }}>
            <button onClick={fetchUserData}>Fetch User</button>
          </div>
        </div>
      );
    }
  }
}

function DisplayLoading() {
  return <div>Loading....</div>;
}

function DisplayData(data) {
  return (
    <div>
      <samp>{JSON.stringify(data, null, 2)}</samp>
    </div>
  );
}

export default GoogleCallback;
