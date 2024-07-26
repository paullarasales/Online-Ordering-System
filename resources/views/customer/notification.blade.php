<x-app-layout>
    <div class="flex flex-col w-full h-screen p-6">
        <div id="notification" class="m-4 p-4 text-black rounded-md">
        
        </div>
    
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const notificationElement = document.getElementById('notification');
    
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
    
                Notif();
            });
        </script>
    </div>
</x-app-layout>