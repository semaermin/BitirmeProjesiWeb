import axios from 'axios';

// Kullanıcı girişi fonksiyonu
export async function login(email, password) {
  // eslint-disable-next-line no-useless-catch
  try {
    const response = await axios.post('http://127.0.0.1:8000/user/login', {
      email: email,
      password: password,
    });

    if (response.status === 200) {
      // Başarılı giriş durumunda token'i geri döndür
      return response.data;
    }
  } catch (error) {
    // Hata durumunda hatayı fırlat
    throw error;
  }
}

// Axios isteklerine token ekleme interceptor'ı
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
