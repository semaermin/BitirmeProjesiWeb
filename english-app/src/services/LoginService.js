import axios from 'axios';

export async function login(email, password) {
  // eslint-disable-next-line no-useless-catch
  try {
    const response = await axios.post('http://127.0.0.1:8000/user/login', {
      email: email,
      password: password,
    });

    if (response.status === 200) {
      return response.data;
    }
  } catch (error) {
    if (error.response.status === 401) {
      alert(
        'Şifreniz ya da e-posta bilgileriniz hatalı lütfen tekrar deneyiniz!'
      );
    }
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
