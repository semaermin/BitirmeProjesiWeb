import axios from 'axios';

export async function login(email, password) {
  // eslint-disable-next-line no-useless-catch
  try {
    const response = await axios.post(
      `${import.meta.env.VITE_API_URL}/user/login`,
      {
        email: email,
        password: password,
      }
    );

    if (response.status === 200) {
      return response.data;
    }
  } catch (error) {
    throw error;
  }
}

export function setAxiosInterceptors(token) {
  axios.interceptors.request.use(
    (config) => {
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );
}
