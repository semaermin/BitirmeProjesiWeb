<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parola Sıfırlama</title>
</head>
<body>
    <p>Merhaba {{ $user->name }},</p>

    <p>Parolanızı sıfırlamak için bir talep aldık. Parolanızı sıfırlamak için lütfen aşağıdaki bağlantıya tıklayın:</p>

    <p><a href="{{ $resetUrl }}">Parola Sıfırla</a></p>

    <p>Eğer parola sıfırlama talebi yapmadıysanız, bu e-postayı güvenle yok sayabilirsiniz.</p>

    <p>Teşekkürler!</p>
</body>
</html>
