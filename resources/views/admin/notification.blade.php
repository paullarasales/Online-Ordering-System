<x-admin-layout>
    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-semibold text-gray-900">New Notifications</h1>
                    <div id="new-notifications-container" class="mt-6">
                        @foreach ($newOrders->reverse() as $order)
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">{{ $order->user->name }} {{ $order->action }} an order</p>
                                <p class="mt-2">Order ID: {{ $order->id }}</p>
                                <p>{{ $order->status }}</p>
                            </div>
                        @endforeach

                        @foreach ($newVerifications->reverse() as $verification)
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-md">{{ $verification->user->name }} submitted a verification</p>
                            </div>
                        @endforeach
                    </div>
                    <h1 class="text-2xl font-semibold text-gray-900 mt-6">All Notifications</h1>
                    <div id="notifications-container" class="mt-6">
                        @foreach ($notifications as $notification)
                            <div class="border-t border-gray-200 py-4">
                                @if($notification->type === 'order')
                                    <p class="text-lg">{{ $notification->user->name }} {{ $notification->action }} an order</p>
                                    <p class="mt-2">Order ID: {{ $notification->id }}</p>
                                    <p>{{ $notification->status }}</p>
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
            async function fetchNewNotifications() {
                try {
                    const response = await fetch('/admin/fetch-orders-and-verifications');
                    const data = await response.json();

                    console.log('Fetched Notifications:', data);

                    if (data.newOrders.length > 0 || data.newVerifications.length > 0) {
                        const newNotificationsContainer = document.getElementById('new-notifications-container');
                        newNotificationsContainer.innerHTML = '';

                        data.newOrders.reverse().forEach(order => {
                            const userName = order.user ? order.user.name : 'Unknown User';
                            const actionText = order.action || 'placed';
                            const orderHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-lg">${userName} ${actionText} an order:</p>
                                    <p class="mt-2">Order ID: ${order.id}</p>
                                    <p>${order.status}</p>
                                </div>
                            `;
                            newNotificationsContainer.insertAdjacentHTML('beforeend', orderHtml);
                        });

                        data.newVerifications.reverse().forEach(verification => {
                            const verificationHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-md">${verification.user.name} submitted a verification</p>
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
