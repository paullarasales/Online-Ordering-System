<x-app-layout>
    <div class="flex items-center justify-center w-full h-screen">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between flex-row">
                <h1 class="text-3xl font-bold mb-4">Verify Your Account</h1>

                <a href="{{ route('userdashboard')}}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mb-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>  
                </a>
            </div>
           
            
            @if($userVerification && $userVerification->verified)
                <p class="text-green-500">Your account is verified.</p>

            @else
                <p class="text-red-500">Your account is not verified. Please upload two valid IDs.</p>
                <form action="{{ route('verify.upload')}}" method="POST" enctype="multipart/form-data" class="mt-4" id="verificationForm">
                    @csrf <!-- CSRF token -->
                    
                    <label for="valid_id1" class="block mb-2">Upload ID 1:</label>
                    <input type="file" id="valid_id1" name="valid_id1" accept="image/*" required onchange="previewImage(this, 'preview1')" class="border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:border-blue-500">
                    <img id="preview1" class="hidden w-32 h-32 object-cover rounded-lg" alt="ID 1 Preview">
                    
                    <label for="valid_id2" class="block mb-2">Upload ID 2:</label>
                    <input type="file" id="valid_id2" name="valid_id2" accept="image/*" required onchange="previewImage(this, 'preview2')" class="border border-gray-300 rounded-lg px-4 py-2 mb-4 focus:outline-none focus:border-blue-500">
                    <img id="preview2" class="hidden w-32 h-32 object-cover rounded-lg" alt="ID 2 Preview">
                    
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">Submit</button>
                </form>
            @endif
        </div>
    </div>
    <script>
        function previewImage(input, imgId) {
            const preview = document.getElementById(imgId);
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
