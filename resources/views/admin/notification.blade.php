<x-admin-layout>
    <div class="py-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900">New Notifications</h1>
                    <div id="new-notifications-container" class="mt-6 space-y-4">
                        @foreach ($newOrders->reverse() as $order)
                            <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    @if ($order->status === 'in-queue')
                                        <p class="text-lg font-medium text-gray-900">{{ $order->user->name }} made an order</p>
                                    @else
                                        <p class="text-lg font-medium text-gray-900">{{ $order->user->name }} {{ $order->action }} an order</p>
                                    @endif
                                    <p class="text-sm text-gray-600">Order ID: {{ $order->id }}</p>
                                    <p class="text-sm text-gray-500">{{ $order->status }}</p>
                                </div>
                            </div>
                        @endforeach

                        @foreach ($newVerifications->reverse() as $verification)
                            <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-medium text-gray-900">{{ $verification->user->name }} submitted a verification</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mt-8">All Notifications</h1>
                    <div id="notifications-container" class="mt-6 space-y-4">
                        @foreach ($notifications as $notification)
                            <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm">
                                <div class="flex-shrink-0">
                                    @if($notification->type === 'order')
                                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @elseif($notification->type === 'verification')
                                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    @if ($notification->type === 'order')
                                        @if ($notification->status === 'Processing')
                                            <p class="text-lg font-medium text-gray-900">{{ $notification->user->name }} made an order</p>
                                        @else
                                            <p class="text-lg font-medium text-gray-900">{{ $notification->user->name }} {{ $notification->action }} an order</p>
                                        @endif
                                        <p class="text-sm text-gray-600">Order ID: {{ $notification->id }}</p>
                                        <p class="text-sm text-gray-500">{{ $notification->status }}</p>
                                    @elseif($notification->type === 'verification')
                                        <p class="text-lg font-medium text-gray-900">{{ $notification->user->name }} submitted a verification</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            async function fetchNewNotifications() {
                try {
                    const response = await fetch('/admin/fetch-orders-and-verifications');
                    const data = await response.json();

                    if (data.newOrders.length > 0 || data.newVerifications.length > 0) {
                        const newNotificationsContainer = document.getElementById('new-notifications-container');
                        newNotificationsContainer.innerHTML = '';

                        data.newOrders.reverse().forEach(order => {
                            const userName = order.user ? order.user.name : 'Unknown User';
                            const actionText = order.action || 'placed';
                            const statusText = order.status === 'processing' ? 'made an order' : `${actionText} an order`;
                            const orderHtml = `
                                <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">${userName} ${statusText}</p>
                                        <p class="text-sm text-gray-600">Order ID: ${order.id}</p>
                                        <p class="text-sm text-gray-500">${order.status}</p>
                                    </div>
                                </div>
                            `;
                            newNotificationsContainer.insertAdjacentHTML('beforeend', orderHtml);
                        });

                        data.newVerifications.reverse().forEach(verification => {
                            const verificationHtml = `
                                <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">${verification.user.name} submitted a verification</p>
                                    </div>
                                </div>
                            `;
                            newNotificationsContainer.insertAdjacentHTML('beforeend', verificationHtml);
                        });

                        alert('New Notification');
                    } else {
                        console.log('No new notifications');
                    }
                } catch (error) {
                    console.error('Error fetching new notifications:', error);
                }
            }
            setInterval(fetchNewNotifications, 4000);
        });
    </script>
</x-admin-layout>
