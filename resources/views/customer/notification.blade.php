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
    
                        let notificationHtml = ''
    
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
                                    <p class="text-lg">Verification is still pending please wait</p>
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
                        
                        if (data.status === 'Processing') {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-lg"> Your order is still processing please wait</p>
                                </div>
                            `;
                        } else if (data.status === 'on deliver') {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-lg"> Your order is currently out for delivery</p>
                                </div>
                            `;
                        } else if (data.status === 'delivered') {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-lg"> Your order has been successfully delivered </p>
                                </div>
                            `;
                        } else if (data.status === 'cancelled') {
                            notificationHtml = `
                                <div class="border-t border-gray-200 py-4">
                                    <p class="text-lg"> Your order has been successfully cancelled </p>
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