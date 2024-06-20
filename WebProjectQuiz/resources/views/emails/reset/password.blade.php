<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parola Sıfırlama</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 20px;">
            <x-application-mark class="block w-auto h-9" />
        </div>
        <h2 style="color: #333333; text-align: center;">Parola Sıfırlama</h2>
        <p style="color: #555555;">Merhaba <strong>{{ $username }}</strong>,</p>

        <p style="color: #555555;">Parolanızı sıfırlamak için bir talep aldık. Parolanızı sıfırlamak için lütfen aşağıdaki bağlantıya tıklayın:</p>

        <p style="text-align: center;">
            <a href="http://localhost:5174/reset-password/{{ $token }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;">Parola Sıfırla</a>
        </p>

        <p style="color: #555555;">Eğer parola sıfırlama talebi yapmadıysanız, bu e-postayı güvenle yok sayabilirsiniz.</p>

        <p style="color: #555555;">Teşekkürler!</p>

        <div style="border-top: 1px solid #dddddd; margin-top: 20px; padding-top: 10px; text-align: center; color: #999999;">
            <p>Şirket Adı</p>
            <p>Adresiniz burada</p>
            <p><a href="mailto:contact@yourdomain.com" style="color: #007bff; text-decoration: none;">contact@yourdomain.com</a></p>
        </div>
    </div>
</body>
</html>
