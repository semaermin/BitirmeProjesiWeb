import React, { useState } from 'react';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';

const FileUpload = () => {
  const { user, setUser } = useTheme();
  const [loading, setLoading] = useState(false);

  const handleFileChange = async (e) => {
    const file = e.target.files[0];

    if (!file) {
      console.error('Dosya seçilmedi.');
      return;
    }

    const formData = new FormData();
    formData.append('profile_photo', file);
    formData.append('user_id', user.id);

    setLoading(true); // Yükleme durumunu başlat

    try {
      const response = await axios.post(
        `http://127.0.0.1:8000/api/profile/update-photo/${user.id}`,
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }
      );

      // Kullanıcı bilgilerini güncelle
      const updatedUser = { ...user, profile_photo: response.data.profile_photo_url };
      setUser(updatedUser);

      // localStorage'ı yeni kullanıcı bilgileriyle güncelle
      localStorage.setItem('user', JSON.stringify(updatedUser));
      
      // Sayfayı yeniden yükle
      // window.location.reload();

    } catch (error) {
      console.error('Profil fotoğrafı güncelleme hatası:', error);
    } finally {
      setLoading(false); // Yükleme durumunu sıfırla
    }
  };

  const handleClick = () => {
    const fileUploadElement = document.getElementById('file-upload');
    if (fileUploadElement) {
      fileUploadElement.click();
    }
  };

  return (
    <div className="file-upload">
      <input
        type="file"
        id="file-upload"
        onChange={handleFileChange}
        style={{ display: 'none' }}
      />
      <button onClick={handleClick} disabled={loading}>
        {loading ? 'Yükleniyor...' : 'Profil Fotoğrafını Değiştir'}
      </button>
    </div>
  );
};

export default FileUpload;
