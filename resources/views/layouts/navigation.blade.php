<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('userdashboard') }}">
                        <img src="{{ asset('logo/2421.png')}}" alt="" style="height:50px;">
                    </a>
                </div>
            </div>
            <!-- Search Bar -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">
                    <input type="text" class="block px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 pl-10 sm:text-sm" placeholder="Search" style="width:800px;">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.293 11.293a1 1 0 011.414 1.414l-2.5 2.5a1 1 0 01-1.414 0 1 1 0 01-.074-1.327l2.5-2.5zM8 13a5 5 0 100-10 5 5 0 000 10z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center justify-end w-56">
                                <div class="flex items-center justify-evenly flex-row w-5/12">
                                    <div>
                                        <x-side-nav-link href="{{ route('notification') }}" :active="request()->routeIs('notification')" class="text-xl text-black font-medium mt-1 flex items-center w-full" id="cart-link">
                                           Notif
                                        <span id="notif-count" class="bg-red-600 text-white text-xs rounded-full px-2 ml-2" style="display: none;">0</span>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center gap-1 rounded-sm h-12">
                                        <x-side-nav-link href="{{ route('chat.index') }}" :active="request()->routeIs('chat.index')" class="text-xl text-black font-medium mt-1 flex items-center w-full" id="chat-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                                            </svg>
                                        </x-side-nav-link>
                                    </div>

                                    <div class="flex items-center gap-1 rounded-sm h-12">
                                        <x-side-nav-link href="{{ route('cart') }}" :active="request()->routeIs('cart')" class="text-xl text-black font-medium mt-1 flex items-center w-full" id="cart-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>
                                        </x-side-nav-link>
                                    </div>

                                    <div>
                                        <x-side-nav-link href="{{ route('view.order') }}" :active="request()->routeIs('view.order')" class="text-xl text-black font-medium mt-1 flex items-center w-full" id="cart-link">
                                           Track My Order
                                        </x-side-nav-link>
                                    </div>


                                </div>

                                <div class="flex gap-2">
                                    @if(Auth::user()->photo)
                                    <img class="w-9 h-9 rounded-full ml-2" src="{{ asset(Auth::user()->photo) }}" alt="Profile Image">
                                    @endif
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="text-gray-800 font-medium">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                                <div class="ms-1 mt-1">
                                    <svg class="fill-current h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('user.profile')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('verify.form')">
                            {{ __('Verify Account')}}
                        </x-dropdown-link>
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var cartLink = document.getElementById('cart-link');
    const chatLink = document.getElementById('chat-link');

    if (cartLink) {
        cartLink.addEventListener('click', function (event) {
            event.stopPropagation();
            window.location.href = cartLink.href;
        });
    }

    if (chatLink) {
        chatLink.addEventListener('click', function (event) {
            event.stopPropagation();
        });
    }

    async function fetchUnreadNotifCount() {
        try {
            const response = await fetch('/unread-notification');
            const data = await response.json();
            console.log(data); // Check the structure of the response here
            const notifElement = document.getElementById('notif-count');

            if (notifElement && data.unreadCount > 0) {
                notifElement.textContent = data.unreadCount;
                notifElement.style.display = 'inline-block';
            } else if (notifElement) {
                notifElement.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching the unread notification count:', error);
        }
    }

    fetchUnreadNotifCount();
    setInterval(fetchUnreadNotifCount, 5000);
});
</script>

