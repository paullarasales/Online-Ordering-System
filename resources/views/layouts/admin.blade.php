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

                    <div class="{{ request()->routeIs('product') ? 'bg-gray-200 w-full' : 'w-44' }} flex items-center gap-2 rounded-sm h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ request()->routeIs('product') ? '#8B5CF6' : '#000000'}}" viewBox="0 0 24 24" stroke-width="1.5" class="ml-10 w-9 h-9">
                            <path fill-rule="evenodd" d="M1.5 7.125c0-1.036.84-1.875 1.875-1.875h6c1.036 0 1.875.84 1.875 1.875v3.75c0 1.036-.84 1.875-1.875 1.875h-6A1.875 1.875 0 0 1 1.5 10.875v-3.75Zm12 1.5c0-1.036.84-1.875 1.875-1.875h5.25c1.035 0 1.875.84 1.875 1.875v8.25c0 1.035-.84 1.875-1.875 1.875h-5.25a1.875 1.875 0 0 1-1.875-1.875v-8.25ZM3 16.125c0-1.036.84-1.875 1.875-1.875h5.25c1.036 0 1.875.84 1.875 1.875v2.25c0 1.035-.84 1.875-1.875 1.875h-5.25A1.875 1.875 0 0 1 3 18.375v-2.25Z" clip-rule="evenodd" />
                        </svg>
                        <x-side-nav-link href="{{ route('product') }}" :active="request()->routeIs('product')" class="text-lg text-black font-medium mt-1 flex items-center w-full">
                            {{ __('Products')}}
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
                            @if(request()->routeIs('dashboard'))
                                <h1 class="text-2xl font-medium">Dashboard</h1>
                            @elseif(request()->routeIs('customer'))
                                <h1 class="text-2xl font-medium">Customer</h1>
                            @elseif(request()->routeIs('order'))
                                <h1 class="text-2xl font-medium">Order List</h1>
                            @elseif(request()->routeIs('product'))
                                <h1 class="text-2xl font-medium">Product</h1>
                            @elseif(request()->routeIs('profile'))
                                <h1 class="text-2xl font-medium">Profile</h1>
                            @elseif(request()->routeIs('admin.newOrders'))
                                <h1 class="text-2xl font-medium">Notification</h1>
                            @elseif(request()->routeIs('message'))
                                <h1 class="text-2xl font-medium">Message</h1>
                            @elseif(request()->routeIs('product-add-view'))
                                <h1 class="text-2xl font-medium">Add new Product</h1>
                            @elseif(request()->routeIs('admin.order.details'))
                                <h1 class="text-2xl font-medium">Order Details</h1>
                            @endif
                        </div>

                        <!-- User Dropdown -->
                        <div class="sm:flex flex items-center justify-center sm:items-center absolute inset-y-0 right-0">
                            <div x-data="{ open: false }" class="relative flex items-center">
                                <!-- Profile Dropdown -->
                                <div x-data="{ open: false }" class="relative ms-4 flex flex-row items-center justify-evenly w-48">
                                    <div class="flex items-center justify-center rounded-sm h-12" style="width: 50px;">
                                        <x-side-nav-link href="{{ route('admin.newOrders') }}" :active="request()->routeIs('admin.newOrders')" class="text-xl text-black font-medium flex items-center w-full">
                                            <div class="relative flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                                </svg>
                                                <span id="notif-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 transform translate-x-1/2 translate-y-1/2" style="display: none;">0</span>
                                            </div>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center justify-center rounded-sm h-12 ml-5" style="width: 60px;">
                                        <x-side-nav-link href="{{ route('message') }}" :active="request()->routeIs('message')" class="text-xl text-black font-medium flex items-center w-full">
                                            <div class="relative flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                                </svg>
                                                <span id="message-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 transform translate-x-1/2 translate-y-1/2" style="display: none;">0</span>
                                            </div>
                                        </x-side-nav-link>
                                    </div>
                                    <button @click="open = !open" class="relative inline-flex items-center px-3 py-2 border border-transparent text-md leading-4 font-lg rounded-md text-black-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        <div class="flex items-center justify-end w-20">
                                            <div class="flex">
                                                @if(Auth::user()->photo)
                                                <img class="w-10 h-10 rounded-full ml-2 border-solid border-2 border-sky-500" src="{{ asset(Auth::user()->photo) }}" alt="Profile Image">
                                                @endif
                                            </div>
                                            <div class="ms-1 mt-1">
                                                <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 sm:w-48 sm:top-full sm:mt-1 sm:ml-6 z-50">
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
                        <div class="relative bg-white overflow-hidden shadow">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notifElement = document.getElementById('notif-count');
            const messageElement = document.getElementById('message-count');

            async function getCount() {
                try {
                    const response = await fetch('/admin/unreadnotification');

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    const data = await response.json();
                    console.log('Fetched counts', data);
                    const totalCount = data.unreadVerification + data.unreadOrder;
                    if (notifElement && totalCount > 0) {
                        notifElement.textContent = totalCount;
                        notifElement.style.display = 'inline-block';
                    } else if (notifElement) {
                        notifElement.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error fetching the count of the notification', error);
                }
            }

            async function getMessageCount() {
                try {
                    const response = await fetch('/unread-messages');

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    const count = data.unreadMessage;

                    if (messageElement && count > 0) {
                        messageElement.textContent = count;
                        messageElement.style.display = 'inline-block';
                    } else if (messageElement) {
                        messageElement.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error fetching the message count', error);
                }
            }

            setInterval(getCount, 3000);
            setInterval(getMessageCount, 3000);
        });
    </script>
    @stack('scripts')
</body>
</html>
