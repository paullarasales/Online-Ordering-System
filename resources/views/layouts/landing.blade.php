<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite('resources/js/app.js')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }

        .top-nav {
            width: 100%; /* Take up 100% of the width */
            padding: 10px 20px; /* Add padding */
            display: flex;
            align-items: center;
            justify-content: space-between; /* Space between elements */
            background-color: #000; /* Background color */
            color: white;

        }

        .top-nav {
            display: flex;
            height: 50px;
            width: 100%;
            align-items: center;
            justify-content: space-evenly;
        }
        
        .top-nav .left {
            width: 15%;
            height: 100%;
        }

        .left {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .left h1 {
            font-size: 100px;
        }

        .top-nav .nav {
            width: 50%;
            height: 100%;
        }

        .nav {
            display:flex;
            align-items: center;
            justify-content: space-evenly;
        }

        .nav a {
            text-decoration: none;
            color: #fff;
        }

        .top-nav .right {
            width: 20%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-evenly;
        }

        .right a {
            text-decoration: none;
            color: #fff;    

        .nav a:hover,
        .right a:hover {
            text-decoration: underline; /* Underline on hover */
        }
        
    </style>
</head>
<body class="font-poppins antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="top-nav">
            <div class="left">
                <h1>2 4 2 1</h1>
            </div>
            <div class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('home') }}">Menu</a>
                <a href="{{ route('home') }}">About</a>
                <a href="{{ route('home') }}">Contact</a>
            </div>
            <div class="right">
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
        <main class="flex-1 h-screen w-full overflow-y-auto rounded-l-md">
            <!-- resources/views/components/landing-layout.blade.php -->
            <div class="min-h-screen bg-red-500 flex items-center justify-center">
                <div>
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
