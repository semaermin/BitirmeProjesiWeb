<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        /* public/css/welcome.css */
body {
    height: 100vh;
    background-image: url('../images/landing-background.png'); /* Arkaplan resminizin yolunu buraya ekleyin */
    background-size: cover;
    background-position: center;
}

.landing-logo {
    text-align: center;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.landing-logo img {
    /* max-width: 50%; */
    height: auto;
    min-width: 50%;
}

.btn {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 0.375rem;
    transition: background-color 0.3s;
}

.background-image{
    height: 100%;
}

.ml-2 {
    margin-left: 0.5rem;
}


    </style>
</head>
<body class="antialiased">
    <div class="relative flex items-center justify-center min-h-screen background-image">
        <div class="text-center landing-logo">
            <!-- Logo -->
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="mb-4 w-25">
            <h2 class="text-white fs-1">Admin Panel</h2>

            <!-- Buttons -->
            @if (Route::has('login'))
                <div class="p-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="mb-4 btn btn-primary btn-login">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-2 btn btn-light">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</body>
</html>
