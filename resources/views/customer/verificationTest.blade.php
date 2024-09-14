<x-app-layout>
    <div class="verification-container">
        <h1>HAHHAHA</h1>
        <div id="verification-content">

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verificationContent = document.getElementById('verification-content');

            async function verificationStatus() {
                try {
                    const response = await fetch('/user/verification/status');
                    if (!response.ok) {
                        console.log('Network response was not ok.');
                    }
                    const data = await response.json();
                    console.log('Status', data);
                    let verificationHTML = '';
                    if (data.status === 'pending') {
                        verificationHTML = `
                            <p class="text-blue-500">Please wait while we verify your account.</p>
                        `;
                    }
                    verificationContent.innerHTML = verificationHTML;
                } catch (error) {
                    console.log('Error'. error);
                }
            }

            setInterval(verificationStatus, 4000);
        });
    </script>
</x-app-layout>