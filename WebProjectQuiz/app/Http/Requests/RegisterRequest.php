<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
     // Bu isteğin yetkilendirilip yetkilendirilmediğini belirleyin.
     public function authorize()
     {
         return true; // Bu, yetkilendirme kontrolünü atlar. İsterseniz özelleştirebilirsiniz.
     }

     // İstek için geçerli doğrulama kurallarını alın.
     public function rules()
     {
         return [
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8',
         ];
     }

     // Hata mesajlarını özelleştirmek için mesajlar fonksiyonunu ekleyebilirsiniz (isteğe bağlı)
     public function messages()
     {
         return [
             'name.required' => 'İsim alanı zorunludur.',
             'email.required' => 'E-posta alanı zorunludur.',
             'email.email' => 'Geçerli bir e-posta adresi girin.',
             'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
             'password.required' => 'Şifre alanı zorunludur.',
             'password.min' => 'Şifre en az 8 karakter olmalıdır.',
         ];
     }
}
