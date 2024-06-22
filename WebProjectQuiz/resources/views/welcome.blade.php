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

    <!-- Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

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
                <a href="{{ url('/dashboard') }}" class="mb-4 btn btn-outline-light btn-login">Dashboard</a>
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
