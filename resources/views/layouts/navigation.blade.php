<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-evenly h-16">
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
                <form action="{{ route('search') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="query" class="block px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 pl-10 sm:text-sm" placeholder="Search" style="width: 600px;">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.293 11.293a1 1 0 011.414 1.414l-2.5 2.5a1 1 0 01-1.414 0 1 1 0 01-.074-1.327l2.5-2.5zM8 13a5 5 0 100-10 5 5 0 000 10z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Search
                    </button>
                </form>
            </div>
            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center justify-center" style="width: 370px;">
                                <div class="flex items-center justify-evenly flex-row w-full">
                                    <div class="flex items-center justify-center rounded-sm h-12 ml-5" style="width: 45px;">
                                        <x-side-nav-link href="{{ route('notification') }}" :active="request()->routeIs('notification')" class="text-xl text-black font-medium flex items-center w-full">
                                            <div class="relative flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                                </svg>
                                                <span id="notif-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 transform translate-x-1/2 translate-y-1/2" style="display: none;">0</span>
                                            </div>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center justify-center rounded-sm h-12 ml-5" style="width: 45px;">
                                        <x-side-nav-link href="{{ route('cart') }}" :active="request()->routeIs('cart')" class="text-xl text-black font-medium mt-1 flex items-center w-full" id="cart-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                            </svg>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center justify-center rounded-sm h-12 ml-5" style="width: 45px;">
                                        <x-side-nav-link href="{{ route('view.order') }}" :active="request()->routeIs('view.order')" class="text-xl text-black font-medium mt-1 flex items-center w-full" id="cart-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                            </svg>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center justify-center rounded-sm h-12 ml-5" style="width: 45px;">
                                        <x-side-nav-link href="{{ route('to-receive') }}" :active="request()->routeIs('to-receive')" class="text-xl text-black font-medium flex items-center w-full">
                                            <div class="relative flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                                </svg>
                                                <span id="to-receive-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 transform translate-x-1/2 translate-y-1/2" style="display: none;">0</span>
                                            </div>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center justify-center rounded-sm h-12 ml-5" style="width: 45px;">
                                        <x-side-nav-link href="{{ route('user.messages') }}" :active="request()->routeIs('user.messages')" class="text-xl text-black font-medium flex items-center w-full">
                                            <div class="relative flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                                </svg>
                                                <span id="message-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 transform translate-x-1/2 translate-y-1/2" style="display: none;">0</span>
                                            </div>
                                        </x-side-nav-link>
                                    </div>
                                    <div class="flex items-center justify-center rounded-sm h-12" style="width: 70px;">
                                        @if(Auth::user()->photo)
                                               <img class="w-full h-full object-cover rounded-full" src="{{ asset(Auth::user()->photo) }}" alt="Profile Image">
                                        @endif
                                        <div class="flex items-center justify-center">
                                            <svg class="fill-current h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
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
    const notifElement = document.getElementById('notif-count');
    const messageElement = document.getElementById('message-count');
    const toReceiveElement = document.getElementById('to-receive-count');

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
            console.log(data);
            const totalNotifCount = data.unreadCount + data.unreadOrderCount;

            if (notifElement && totalNotifCount > 0) {
                notifElement.textContent = totalNotifCount;
                notifElement.style.display = 'inline-block';
            } else if (notifElement) {
                notifElement.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching the unread notification count:', error);
        }
    }

    //for fetching message count
    async function getMessageCount() {
        try {
            const response = await fetch('/user/unread-messages');
            if (!response.ok) {
                throw new Error('Network was not ok');
            }

            const data = await response.json();
            console.log('Fetched count:', data);
            const countMessage = data.unreadMessage;

            if (messageElement && countMessage > 0) {
                messageElement.textContent = countMessage;
                messageElement.style.display = 'inline-block'
            } else if (messageElement) {
                messageElement.style.display = 'none';
            }
        } catch (error) {
            console.error('Error fetching the unread messages:', error);
        }
    }

    async function toReceiveCount() {
      try {
        const response = await fetch('/to-receive-unread');
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const data = await response.json();
        console.log('Fetched to receive order', data);
        const toReceiveCount = data.unreadToReceive;

        const toReceiveElement = document.getElementById('to-receive-count');
        if (toReceiveElement) {
          if (toReceiveCount > 0) {
            toReceiveElement.textContent = toReceiveCount;
            toReceiveElement.style.display = 'inline-block';
          } else {
            toReceiveElement.style.display = 'none';
          }
        }
      } catch (error) {
        console.error('Error fetching the to receive order count:', error);
      }
    }

    document.addEventListener('DOMContentLoaded', toReceiveCount);

    setInterval(getMessageCount, 1000);
    setInterval(fetchUnreadNotifCount, 1000);
    setInterval(toReceiveCount, 1000);
   });
</script>
