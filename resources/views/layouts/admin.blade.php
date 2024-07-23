<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ordering System') }}</title>

    <link rel="shortcut icon" href="{{ asset('logo/no-bg.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,500;1,600&family=Rubik+Broken+Fax&display=swap" rel="stylesheet">

    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <!-- Scripts -->
    <script {{ asset('js/app.js') }} defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins antialiased">
    <div class="relative min-h-screen md:flex" x-data="{ open: true }">
        <!-- Sidebar -->
         <aside :class="{ '-translate-x-full': !open }" class="z-10 bg-white text-black w-64 px-2 py-4 absolute inset-y-0 left-0 md:relative transform md:translate-x-0 transition ease-in-out duration-200">
                <!-- Logo -->
                <div class="flex items-center justify-between">
                    <div class="flex flex-row items-start space-y-2 w-full">
                        <header>
                            <a href="{{ route('dashboard') }}" class="flex items-center justify-center flex-row">
                                <img src="{{ asset('logo/no-bg.png')}}" alt="" class="h-20 w-20">
                                <div class="flex flex-col">
                                    <div>
                                        <h1 class="font-md text-2xl">Twenty Four</h1>
                                    </div>
                                    <div class="ml-4">
                                        <h1 class="font-md text-2xl">Twenty One</h1>
                                    </div>
                                </div>
                            </a>
                        </header>
                    </div>

                    <button type="button" @click="open = !open" class="sm:hidden inline-flex p-2 items-center justify-center rounded-md text-black hover:bg-blue-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="block w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex flex-col mt-10 p-3 gap-3 w-full">
                    <div class="{{ request()->routeIs('dashboard') ? 'bg-gray-200 w-full text-2xl font-md' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ request()->routeIs('dashboard') ? '#8B5CF6' : '#000000' }}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-9 h-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <x-side-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-lg text-black font-medium mt-1 flex items-start">
                            {{ __('Dashboard')}}
                        </x-side-nav-link>
                    </div>

                    <div class="{{ request()->routeIs('customer') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ request()->routeIs('customer') ? '#8B5CF6' : '#00000'}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-9 h-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                        <x-side-nav-link href="{{ route('customer') }}" :active="request()->routeIs('customer')" class="text-lg text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Customers')}}
                        </x-side-nav-link>
                    </div>

                    <div class="{{ request()->routeIs('order') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ request()->routeIs('order') ? '#8B5CF6' : ''}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-9 h-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <x-side-nav-link href="{{ route('order') }}" :active="request()->routeIs('order')" class="text-lg text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Order List')}}
                        </x-side-nav-link>
                    </div>

                    <div class="{{ request()->routeIs('message') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="{{ request()->routeIs('message') ? '#8B5CF6' : ''}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-9 h-9">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.068.157 2.148.279 3.238.364.466.037.893.281 1.153.671L12 21l2.652-3.978c.26-.39.687-.634 1.153-.67 1.09-.086 2.17-.208 3.238-.365 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <x-side-nav-link href="{{ route('message') }}" :active="request()->routeIs('message')" class="text-lg text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Chats')}}
                        </x-side-nav-link>
                    </div>

                    <div class="{{ request()->routeIs('product') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ request()->routeIs('product') ? '#8B5CF6' : '#000000'}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-9 h-9">
                            <path fill-rule="evenodd" d="M1.5 7.125c0-1.036.84-1.875 1.875-1.875h6c1.036 0 1.875.84 1.875 1.875v3.75c0 1.036-.84 1.875-1.875 1.875h-6A1.875 1.875 0 0 1 1.5 10.875v-3.75Zm12 1.5c0-1.036.84-1.875 1.875-1.875h5.25c1.035 0 1.875.84 1.875 1.875v8.25c0 1.035-.84 1.875-1.875 1.875h-5.25a1.875 1.875 0 0 1-1.875-1.875v-8.25ZM3 16.125c0-1.036.84-1.875 1.875-1.875h5.25c1.036 0 1.875.84 1.875 1.875v2.25c0 1.035-.84 1.875-1.875 1.875h-5.25A1.875 1.875 0 0 1 3 18.375v-2.25Z" clip-rule="evenodd" />
                        </svg>
                        <x-side-nav-link href="{{ route('product') }}" :active="request()->routeIs('product')" class="text-lg text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Products')}}
                        </x-side-nav-link>
                    </div>

                    <div class="{{ request()->routeIs('profile') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ request()->routeIs('profile') ? '#8B5CF6' : '#000000'}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-6 h-6">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>

                        <x-side-nav-link href="{{ route('profile') }}" :active="request()->routeIs('profile')" class="text-xl text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Profile')}}
                        </x-side-nav-link>
                    </div>

                    <div class="{{ request()->routeIs('admin.newOrders') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ request()->routeIs('admin.newOrders') ? '#8B5CF6' : '#000000'}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-6 h-6">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>

                        <x-side-nav-link href="{{ route('admin.newOrders') }}" :active="request()->routeIs('admin.newOrders')" class="text-xl text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Notifaction')}}
                        </x-side-nav-link>
                    </div>
                </nav>
            </aside>
        <!-- Main -->
        <main class="flex-1 h-screen w-full overflow-y-auto rounded-l-md">
            <!-- Top Navigation -->
            <nav class="">
                <div class="mx-auto px-2 sm:px-6 lg:px-8">
                    <div class="relative flex items-center justify-between h-16">
                        <div>
                            <h1 class="text-2xl font-medium">Welcome Back {{ Auth::user()->name }}</h1>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="sm:flex flex items-center justify-center sm:items-center absolute inset-y-0 right-0">
                            <div x-data="{ open: false }" class="relative flex items-center">
                                <!-- Notification Icon -->
                                <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-md leading-4 font-lg rounded-md text-black-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.403-2.806A2.25 2.25 0 0 0 17.75 12H6.25a2.25 2.25 0 0 0-2.847 1.194L2 17h5m8 0v2.25a2.25 2.25 0 1 1-4.5 0V17m2.25 0h-2.25M15 17h5m-5 0v2.25a2.25 2.25 0 1 1-4.5 0V17m2.25 0H15zM3 17h5" />
                                    </svg>
                                </button>
                                
                                <!-- Profile Dropdown -->
                                <div x-data="{ open: false }" class="relative ms-4">
                                    <button @click="open = !open" class="inline-flex items-center px-3 py-2 border border-transparent text-md leading-4 font-lg rounded-md text-black-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div class="flex items-center justify-end w-48">
                                            <div class="flex gap-2">
                                                @if(Auth::user()->photo)
                                                <img class="w-9 h-9 rounded-full ml-2" src="{{ asset(Auth::user()->photo) }}" alt="Profile Image">
                                                @endif
                                            </div>
                                            <div class="ms-1 mt-1">
                                                <svg class="fill-current h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 sm:w-48 sm:top-full sm:mt-1 sm:ml-6">
                                        <div class="py-1">
                                            <x-dropdown-link :href="route('profile')">
                                                {{ __('Edit Profile') }}
                                            </x-dropdown-link>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();">
                                                    {{ __('Log Out') }}
                                                </x-dropdown-link>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Main Content -->
            <div class="flex flex-col md:flex-row w-full md:h-full md:gap-2">
                <div class="flex-1">
                    <div class="mx-auto px-2 sm:px-6 lg:px-8">
                        <div class="relative py-4 bg-white overflow-hidden shadow sm:rounded-lg">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
