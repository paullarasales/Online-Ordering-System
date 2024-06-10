<x-admin-layout>
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-4xl bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-xl font-semibold mb-4">Add New Product</h1>
            <form action="{{ route('products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left container for product information -->
                    <div>
                        <div class="mb-4">
                            <label for="productName" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" id="productName" name="product_name" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="text" id="price" name="price" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 p-2 block w-full border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="stockQuantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                            <input type="number" id="stockQuantity" name="stockQuantity" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        </div>
                        <!-- Dropdown for selecting category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category_id" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                                @foreach($category as $categories)
                                    <option value="{{ $categories->id }}">{{ $categories->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <div class="flex items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-md relative">
                                <input type="file" id="image" name="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage()">
                                <div id="imagePreview" class="absolute inset-0 w-full h-full overflow-hidden"></div>
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path d="M44 22a4 4 0 00-4-4h-8V8a4 4 0 00-4-4H16a4 4 0 00-4 4v10H4a4 4 0 00-4 4v12a4 4 0 004 4h10v10a4 4 0 004 4h12a4 4 0 004-4v-10h10a4 4 0 004-4V26zM24 40a16 16 0 110-32 16 16 0 010 32z"></path>
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">Click or drag image here</p>
                                </div>
                            </div>
                        </div>
                      
                        <div class="flex justify-end" style="margin-top: 50px;">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Save</button>
                            <button type="button" class="bg-red-500 text-white ml-2 px-4 py-2 rounded-md hover:bg-red-600">Cancel</button>
                        </div>
                    </div>
                </div>
                <!-- Hidden input field to hold the uploaded image file -->
                <input type="hidden" id="uploadedImage" name="uploadedImage">
            </form>
        </div>
    </div>
    <script>
        function previewImage() {
            const fileInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const uploadedImageInput = document.getElementById('uploadedImage');
            
            const file = fileInput.files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.classList.add('w-full', 'h-auto');
                
                imagePreview.innerHTML = '';
                imagePreview.appendChild(img);
                
                // Set the base64-encoded image data to the hidden input field
                uploadedImageInput.value = event.target.result;
            };
            
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-admin-layout>