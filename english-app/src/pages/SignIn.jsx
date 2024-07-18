// src/SignIn.js

import React, { useState, useEffect } from 'react';

function SignIn() {
  const [loginUrl, setLoginUrl] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`${import.meta.env.VITE_API_URL}/api/auth`, {
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    })
      .then((response) => {
        if (response.ok) {
          return response.json();
        }
        throw new Error('Something went wrong!');
      })
      .then((data) => {
        setLoginUrl(data.url);
        setLoading(false);
      })
      .catch((error) => {
        console.error(error);
        setLoading(false);
      });
  }, []);

  return (
    <div>
      {loading ? (
        <>Google ile Giriş Yap</>
      ) : (
        loginUrl != null && <a href={loginUrl}>Google ile Giriş Yap</a>
      )}
    </div>
  );
}

export default SignIn;
