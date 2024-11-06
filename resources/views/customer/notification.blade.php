<x-app-layout>
    <div class="flex flex-col w-full h-screen p-6 bg-gray-50">
        <div id="notification-order" class="m-4 p-4 bg-white text-black rounded-md shadow-md border border-gray-200">

        </div>
        <div id="notification" class="m-4 p-4 bg-white text-black rounded-md shadow-md border border-gray-200">

        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const notificationElement = document.getElementById('notification');
                const notificationOrderHtml = document.getElementById('notification-order');

                async function Notif() {
                    try {
                        const response = await fetch('/image/status');
                        const data = await response.json();
                        console.log(data);

                        let notificationHtml = '';

                        if (data.status === 'verified') {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4 flex items-center">
                                    <span class="text-green-500 text-2xl mr-2">&#10003;</span>
                                    <p class="text-lg font-semibold">You are now a verified user</p>
                                </div>
                            `;
                        } else if (data.status === 'rejected') {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4 flex items-center">
                                    <span class="text-red-500 text-2xl mr-2">&#10060;</span>
                                    <p class="text-lg font-semibold">Unfortunately, your ID could not be verified</p>
                                </div>
                            `;
                        } else {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4 flex items-center">
                                    <span class="text-yellow-500 text-2xl mr-2">&#9888;</span>
                                    <p class="text-lg font-semibold">Verification is still pending, please wait</p>
                                </div>
                            `;
                        }

                        notificationElement.innerHTML = notificationHtml;
                    } catch (error) {
                        console.error('Error fetching notifications', error);
                    }
                }

                async function OrderNotif() {
                    try {
                        const response = await fetch('/order/status');
                        const data = await response.json();

                        console.log(data);

                        let notificationHtml = '';

                        if (data.orders && data.orders.length > 0) {
                            data.orders.forEach(order => {
                                order.products.forEach(product => {
                                    let statusMessage = `<strong>${product.product_name}</strong>`;

                                    switch (order.status) {
                                        case 'in-queue':
                                            statusMessage += " is still in queue. We will notify you later.";
                                            break;
                                        case 'processing':
                                            statusMessage += " is currently being processed. Please wait.";
                                            break;
                                        case 'on-deliver':
                                            statusMessage += " is out for delivery.";
                                            break;
                                        case 'delivered':
                                            statusMessage += " has been successfully delivered. Enjoy!";
                                            break;
                                        case 'cancelled':
                                            statusMessage += " has been cancelled.";
                                            break;
                                    }

                                    notificationHtml += `
                                        <div class="border-t border-gray-200 py-4 flex items-start">
                                            <span class="text-blue-500 text-2xl mr-2">&#128230;</span>
                                            <p>${statusMessage}</p>
                                        </div>
                                    `;
                                });
                            });
                        } else {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4 flex items-center">
                                    <span class="text-gray-500 text-2xl mr-2">&#128275;</span>
                                    <p class="text-lg">No orders found</p>
                                </div>
                            `;
                        }

                        notificationOrderHtml.innerHTML = notificationHtml;
                    } catch (error) {
                        console.log('Error fetching order status', error);
                    }
                }

                OrderNotif();
                Notif();
            });
        </script>
    </div>
</x-app-layout>
