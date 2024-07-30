<x-app-layout>
    <div class="flex flex-col w-full h-screen p-6">
        <div id="notification-order" class="m-4 p-4 text-black rounded-md">

        </div>
        <div id="notification" class="m-4 p-4 text-black rounded-md">
        
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
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">You are now a verified user</p>
                            </div>
                        `;
                    } else if (data.status === 'rejected') {
                        notificationHtml = `
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">Unfortunately, your ID could not be verified</p>
                            </div>
                        `;
                    } else {
                        notificationHtml = `
                            <div class="border-t border-gray-200 py-4">
                                <p class="text-lg">Verification is still pending, please wait</p>
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
                                let statusMessage = `Your order ${product.product_name}`;
                            
                                switch (order.status) {
                                    case 'Processing':
                                        statusMessage += " is still processing. Please wait. ";
                                        break;
                                    case 'on deliver':
                                        statusMessage += " is currently out of delivery.";
                                        break;
                                    case 'delivered':
                                        statusMessage += " has been successfully delivered. We hope you enjoy your order.";
                                        break;
                                    case 'cancelled':
                                        statusMessage += " has been cancelled.";
                                        break;
                                }
                                notificationHtml += `
                                <div class="border-t border-gray-200 py-4">
                                    <p>${statusMessage}</p>
                                </div>
                            `;
                            });
                        });
                    } else {
                        notificationHtml = `
                            <div class="border-t border-gray-200 py-4">
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