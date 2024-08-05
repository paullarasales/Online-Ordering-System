<x-admin-layout>
    <div class="flex flex-col w-full h-screen p-6">
        <!-- Header -->
        <div class="flex flex-row justify-between w-full h-10">
            <div id="alert-container" class="fixed top-4 right-4 z-50"></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            let ordersNotified = false;
            let verificationNotified = false;

            async function getNotif() {
                try {
                    const response = await fetch('/admin/fetch-only');
                    const data = await response.json();
                    const notificationShown = localStorage.getItem('notificationShown');

                    console.log(data);
                    console.log('Order notification shown', notificationShown);

                    if (data.orders && data.orders.length > 0) {
                        if (!ordersNotified) {
                            showCustomAlert('New Orders', '{{ route('admin.newOrders')}}', '{{ asset('audio/order.mp3')}}');
                            ordersNotified = true;
                        }
                    } else {
                        ordersNotified = false;
                    }
                } catch (error) {
                    console.error('Error fetching order', error);
                }
            }

            async function getNotifVerification() {
                try {
                    const response = await fetch('/admin/fetch-only-verifications');
                    const data = await response.json();

                    console.log('Verification data', data);

                    if (data.verifications && data.verifications.length > 0) {
                        if (!verificationNotified) {
                            showCustomAlert('New Verification', '{{ route('admin.newOrders')}}', '{{ asset('audio/verification.mp3')}}');
                            verificationNotified = true;
                        }
                    } else {
                        verificationNotified = false;
                    }
                } catch (error) {
                    console.error('Error fetching verification', error);
                }
            }

            function showCustomAlert(message, redirectUrl, audioFile) {
                const alertContainer = document.getElementById('alert-container');

                const alert = document.createElement('div');

                alert.classList.add('max-w-sm', 'w-full', 'bg-white', 'shadow-md', 'rounded-md', 'pointer-events-auto', 'ring-1', 'ring-black', 'ring-opacity-5', 'overflow-hidden', 'm-4', 'p-4', 'flex', 'justify-between', 'items-center', 'space-x-4');

                alert.innerHTML = `
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4H8m4-4H8m-4 8h16M5 12h2m4-4h2v4h1M58h2m-2 8h2M3 16h18" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-auto">
                        <button id="redirect-btn" class="bg-blue-500 text-white px-3 py-1 rounded-md">View</button>
                    </div>
                `;

                alert.querySelector('#redirect-btn').addEventListener('click', () => {
                    window.location.href = redirectUrl;
                });

                alertContainer.appendChild(alert);

                const audio = new Audio(audioFile);
                audio.play();

                setTimeout(() => {
                    alert.remove();
                    localStorage.removeItem('notificationShown');
                }, 3000);
            }
            setInterval(getNotif, 5000);
            setInterval(getNotifVerification, 5000);
        });
    </script>
</x-admin-layout>
