<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if(session('login'))
    <p>Anlık olarak oturumu açık olan kullanıcılar:</p>
    <ul>
        @foreach(session('login') as $userId)
            <li>{{ \App\Models\User::find($userId)->name }}</li>
        @endforeach
    </ul>
    @else
        <p>Anlık olarak oturum açık olan kullanıcı bulunmamaktadır.</p>
    @endif


</body>
</html>
