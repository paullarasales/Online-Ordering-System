<x-admin-layout>
    <div class="py-6 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900">New Notifications</h1>
                    <div id="new-notifications-container" class="mt-6 space-y-4">
                        @foreach ($newOrders->reverse() as $order)
                            <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm hover:bg-gray-50 transition-colors">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    @if ($order->status === 'Processing')
                                        <p class="text-lg font-medium text-gray-900">{{ $order->user->name }} made an order.</p>
                                    @else
                                        <p class="text-lg font-medium text-gray-900">{{ $order->user->name }} made an order.</p>
                                    @endif 
                                </div>
                            </div>
                        @endforeach
                        @foreach ($newVerifications->reverse() as $verification)
                            <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm hover:bg-gray-50 transition-colors">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-medium text-gray-900">{{ $verification->user->name }} submitted a verification request.</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mt-8">All Notifications</h1>
                    <div id="notifications-container" class="mt-6 space-y-4">
                        @foreach ($notifications as $notification)
                            @if ($notification->type === 'order')
                                <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm mb-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex-shrink-0">
                                        <!-- Icon depending on status -->
                                        @if ($notification->status === 'processing')
                                            <svg class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        @elseif ($notification->status === 'in-queue')
                                            <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                        @elseif ($notification->status === 'delivered')
                                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif ($notification->status === 'on-deliver')
                                            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif ($notification->status === 'canceled')
                                            <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <!-- Main message content -->
                                        @if ($notification->status === 'processing')
                                            <p class="text-lg font-medium text-gray-900">Order ID: {{ $notification->id }} from {{ $notification->user->name }} is currently being processed.</p>
                                        @elseif ($notification->status === 'in-queue')
                                            <p class="text-lg font-medium text-gray-900">Order ID: {{ $notification->id }} from {{ $notification->user->name }} is still in queue.</p>
                                        @elseif ($notification->status === 'on-deliver')
                                            <p class="text-lg font-medium text-gray-900">Order ID: {{ $notification->id }} from {{ $notification->user->name }} is out for delivery.</p>
                                        @elseif ($notification->status === 'delivered')
                                            <p class="text-lg font-medium text-gray-900">Order ID: {{ $notification->id }} from {{ $notification->user->name }} has been delivered.</p>
                                        @elseif ($notification->status === 'canceled')
                                            <p class="text-lg font-medium text-gray-900">Order ID: {{ $notification->id }} from {{ $notification->user->name }} has been canceled.</p>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($notification->type === 'verification')
                                <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm mb-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">{{ $notification->user->name }} submitted a verification request.</p>
                                    </div>
                                </div>
                            @endif
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
                            const statusText = order.status === 'Processing' ? 'is currently being processed' : 'action performed on the order';
                            const orderHtml = `
                                <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm hover:bg-gray-50 transition-colors">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">Order ID: ${order.id} from ${userName} ${statusText}.</p>
                                    </div>
                                </div>
                            `;
                            newNotificationsContainer.innerHTML += orderHtml;
                        });

                        data.newVerifications.reverse().forEach(verification => {
                            const userName = verification.user ? verification.user.name : 'Unknown User';
                            const verificationHtml = `
                                <div class="flex items-center p-4 border rounded-lg bg-white shadow-sm hover:bg-gray-50 transition-colors">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-lg font-medium text-gray-900">${userName} submitted a verification request.</p>
                                    </div>
                                </div>
                            `;
                            newNotificationsContainer.innerHTML += verificationHtml;
                        });
                    }
                } catch (error) {
                    console.error('Error fetching notifications:', error);
                }
            }

            fetchNewNotifications();
        });
    </script>
</x-admin-layout>
