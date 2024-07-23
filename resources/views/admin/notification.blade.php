<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900">New Notification</h1>
                    <div id="new-orders-container" class="mt-6">
                        <!-- New orders will be dynamically added here -->
                        @foreach ($newOrders->reverse() as $order)
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">{{ $order->user->name }} placed an order</p>
                                <p class="mt-2">Order ID: {{ $order->id }}</p>
                                <p>{{ $order->status}}</p>
                            </div>
                        @endforeach

                        @foreach ($newVerifications->reverse() as $verification)
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-md">{{$verification->user->name}} submitted a verification</p>
                            </div>
                        @endforeach
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-900 mt-6">All Notifications</h1>
                    <div id="notifications-container" class="mt-6">
                        @foreach ($notifications as $notification)
                            <div class="border-t border-gray-200 py-4">
                                @if($notification->type === 'order')
                                    <p class="text-lg">{{ $notification->user->name }} placed an order</p>
                                    <p class="mt-2">Order ID: {{ $notification->id }}</p>
                                @elseif($notification->type === 'verification')
                                    <p class="text-lg">{{ $notification->user->name }} submitted a verification</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
     document.addEventListener('DOMContentLoaded', async () => {
        async function fetchNewOrdersAndUpdate() {
            try {
                const response = await fetch('/admin/fetch-new-orders');
                const data = await response.json();

                console.log('Fetched Data:', data);

                if (data.newOrders.length > 0) {
                    const newOrdersContainer = document.getElementById('new-orders-container');
                    newOrdersContainer.innerHTML = '';

                    data.newOrders.reverse().forEach(order => {
                        const userName = order.user ? order.user.name : 'Unknown User';
                        const orderHtml = `
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">${userName} placed an order:</p>
                                <p class="mt-2">Order ID: ${order.id}</p>
                                <p>${order.status}</p>
                            </div>
                        `;
                        newOrdersContainer.insertAdjacentHTML('beforeend', orderHtml);
                    });

                    alert('New Order');
                } else {
                    console.log('No new orders');
                }
            } catch (error) {
                console.error('Error fetching new orders:', error);
            }
        }

        async function fetchNewVerifications() {
            try {
                const response = await fetch('/admin/fetch-new-verifications');
                const data = await response.json();
                
                console.log('fetched verifications', data); 

                if (data.newVerifications.length > 0) {
                    const newVerificationsContainer = document.getElementById('new-orders-container');
                    newVerificationsContainer.innerHTML = '';

                    data.newVerifications.reverse().forEach(verification => {
                        const verificationHtml = `
                            <div class="border-t border-gray-200 py-4">
                            <p class="text-md">${verification.user.name} fucku</p>
                            </div>
                        `;
                        newVerificationsContainer.insertAdjacentHTML('beforeend', verificationHtml);
                    });
                } else {
                    console.log('No new verifications');
                }
            } catch (error) {
                console.error('Error fetching new verifications', error);
            }
        }


        setInterval(fetchNewOrdersAndUpdate, 4000);
        setInterval(fetchNewVerifications, 4000);
    });


    </script>
</x-admin-layout>
