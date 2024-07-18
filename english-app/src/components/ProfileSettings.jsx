import React, { useState } from 'react';
import axios from 'axios';
import { useTheme } from '../context/ThemeContext';
import '../assets/styles/components/profile-settings.scss';
import { ToastContainer, toast } from 'react-toastify';

const ProfileSettings = () => {
  const { theme, user, setUser } = useTheme();
  const [loading, setLoading] = useState(false);
  const [passwordMessage, setPasswordMessage] = useState('');
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [newPasswordConfirmation, setNewPasswordConfirmation] = useState('');

  const handleFileChange = async (e) => {
    const file = e.target.files[0];

    if (!file) {
      toast.error('Dosya seçilemedi!');
      return;
    }

    if (file.size > 2 * 1024 * 1024) {
      toast.error(
        'Dosya boyutu çok büyük, maksimum 2 MB dosya büyüklüğü desteklenmektedir.'
      );
      return;
    }

    const formData = new FormData();
    formData.append('profile_photo', file);
    formData.append('user_id', user.id);

    !loading ? toast.info('Yükleniyor') : '';

    try {
      const response = await axios.post(
        `${import.meta.env.VITE_API_URL}/api/profile/update-photo/${user.id}`,
        formData,
        {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }
      );

      const updatedUser = {
        ...user,
        profile_photo_path: response.data.user.profile_photo_path,
      };
      setUser(updatedUser);

      window.location.reload();

      // localStorage'ı yeni kullanıcı bilgileriyle güncelle
      localStorage.setItem('user', JSON.stringify(updatedUser));

      toast.success(response.data.success);
    } catch (error) {
      alert.error('Profil fotoğrafı güncelleme hatası:', error);
      toast.error('Profil fotoğrafı güncellenirken bir hata oluştu.');
    } finally {
      setLoading(false);
    }
  };

  const handleClick = () => {
    const fileUploadElement = document.getElementById('file-upload');
    if (fileUploadElement) {
      fileUploadElement.click();
    }
  };

  const handlePasswordUpdate = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.put(
        `${import.meta.env.VITE_API_URL}/api/profile/update-password`,
        {
          current_password: currentPassword,
          new_password: newPassword,
          new_password_confirmation: newPasswordConfirmation,
          user_id: user.id, // Kullanıcı bilgisini isteğe eklem
        }
      );

      // Başarılı yanıtın içinde success veya message bilgisini kontrol edin
      if (response.data.success) {
        toast.success('Şifre başarıyla güncellendi');
      } else {
        toast.info(response.data.message); // Veya başka bir mesaj alanı
      }
    } catch (error) {
      console.log(error);
      toast.error('Şifre güncelleme hatası!');
      //bad request olursa mevcut şifre yanlış
      setPasswordMessage(
        'Mevcut şifrenizin doğru olduğundan emin olunuz ve parolaların uyuştuğundan emin olunuz!'
      );
    }
  };

  return (
    <div id="profileSettings" className={theme}>
      <div className="profile-setting-container">
        <div className="file-upload">
          <input
            type="file"
            id="file-upload"
            accept="image/*"
            onChange={handleFileChange}
            style={{ display: 'none' }}
          />
          <button
            onClick={handleClick}
            disabled={loading}
            title="Maksimum 2 MB dosya büyüklüğü desteklenmektedir."
          >
            Profil Fotoğrafını Değiştir
          </button>
        </div>
        <div className="password-update-form">
          <h3>Şifre Güncelle</h3>
          {passwordMessage && <p>{passwordMessage}</p>}
          <form onSubmit={handlePasswordUpdate}>
            <div className="profile-password-wrapper">
              <label htmlFor="current_password">Mevcut Şifre:</label>
              <input
                type="password"
                id="current_password"
                value={currentPassword}
                onChange={(e) => setCurrentPassword(e.target.value)}
                required
              />
            </div>
            <div className="profile-password-wrapper">
              <label htmlFor="new_password">Yeni Şifre:</label>
              <input
                type="password"
                id="new_password"
                value={newPassword}
                onChange={(e) => setNewPassword(e.target.value)}
                required
              />
            </div>
            <div className="profile-password-wrapper">
              <label htmlFor="new_password_confirmation">
                Yeni Şifre (Tekrar):
              </label>
              <input
                type="password"
                id="new_password_confirmation"
                value={newPasswordConfirmation}
                onChange={(e) => setNewPasswordConfirmation(e.target.value)}
                required
              />
            </div>
            <button type="submit">Şifre Güncelle</button>
          </form>
        </div>
      </div>
      <ToastContainer
        position="bottom-right"
        autoClose={5000}
        limit={8}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnFocusLoss
        draggable
        pauseOnHover
        theme={theme}
      />
    </div>
  );
};

export default ProfileSettings;
