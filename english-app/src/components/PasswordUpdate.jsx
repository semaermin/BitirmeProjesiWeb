import React, { useState } from 'react';
import { useTheme } from '../context/ThemeContext';
import axios from 'axios';

export default function PasswordUpdateForm() {
  const { user, theme } = useTheme();
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [newPasswordConfirmation, setNewPasswordConfirmation] = useState('');
  const [message, setMessage] = useState('');

  const handlePasswordUpdate = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.put('http://127.0.0.1:8000/api/profile/update-password', {
        current_password: currentPassword,
        new_password: newPassword,
        new_password_confirmation: newPasswordConfirmation,
        user_id: user.id, // Kullanıcı bilgisini isteğe eklem
      });

      // Başarılı yanıtın içinde success veya message bilgisini kontrol edin
      if (response.data.success) {
        setMessage('Şifre başarıyla güncellendi');
      } else {
        setMessage(response.data.message); // Veya başka bir mesaj alanı
      }
    } catch (error) {
      console.error('Şifre güncelleme hatası:', error);
      //bad request olursa mevcut şifre yanlış
      setMessage('Şifre güncellenirken bir hata oluştu');
    }
  };

  return (
    <div className="password-update-form">
      <h3>Şifre Güncelle</h3>
      {message && <p>{message}</p>}
      <form onSubmit={handlePasswordUpdate}>
        <div>
          <label htmlFor="current_password">Mevcut Şifre:</label>
          <input
            type="password"
            id="current_password"
            value={currentPassword}
            onChange={(e) => setCurrentPassword(e.target.value)}
            required
          />
        </div>
        <div>
          <label htmlFor="new_password">Yeni Şifre:</label>
          <input
            type="password"
            id="new_password"
            value={newPassword}
            onChange={(e) => setNewPassword(e.target.value)}
            required
          />
        </div>
        <div>
          <label htmlFor="new_password_confirmation">Yeni Şifre (Tekrar):</label>
          <input
            type="password"
            id="new_password_confirmation"
            value={newPasswordConfirmation}
            onChange={(e) => setNewPasswordConfirmation(e.target.value)}
            required
          />
        </div>
        <button type="submit">Güncelle</button>
      </form>
    </div>
  );
}
