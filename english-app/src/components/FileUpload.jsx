import React, { useState } from 'react';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';

const FileUpload = () => {
  const { user, setUser } = useTheme();
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState(''); // Mesaj durumu ekleyin

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
      const updatedUser = { ...user, profile_photo_path: response.data.user.profile_photo_path };
      setUser(updatedUser);

      // localStorage'ı yeni kullanıcı bilgileriyle güncelle
      localStorage.setItem('user', JSON.stringify(updatedUser));

      // Başarılı mesajı ayarla
      setMessage(response.data.success);

    } catch (error) {
      console.error('Profil fotoğrafı güncelleme hatası:', error);
      // Hata mesajı ayarla
      setMessage('Profil fotoğrafı güncellenirken bir hata oluştu.');
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
      {message && <p>{message}</p>} {/* Mesajı görüntüleyin */}
    </div>
  );
};

export default FileUpload;
