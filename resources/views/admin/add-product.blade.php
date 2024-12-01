<x-admin-layout>
    <div class="flex justify-center items-center h-screen bg-white">
        <div class="w-full max-w-4xl bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Add New Product</h1>
            <form action="{{ route('products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left container for product information -->
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <div class="mb-4">
                            <label for="productName" class="block text-sm font-medium text-gray-600">Product Name</label>
                            <input type="text" id="productName" name="product_name" placeholder="Enter product name" class="mt-1 p-3 block w-full border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-600">Price</label>
                            <input type="text" id="price" name="price" placeholder="Enter price" class="mt-1 p-3 block w-full border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Enter product description" class="mt-1 p-3 block w-full border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-600">Category</label>
                            <select id="category" name="category_id" class="mt-1 p-3 block w-full border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                @foreach($category as $categories)
                                    <option value="{{ $categories->id }}">{{ $categories->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-600">Image</label>
                            <div class="flex items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg relative bg-gray-200">
                                <input type="file" id="image" name="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage()">
                                <div id="imagePreview" class="absolute inset-0 w-full h-full overflow-hidden flex items-center justify-center"></div>
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path d="M44 22a4 4 0 00-4-4h-8V8a4 4 0 00-4-4H16a4 4 0 00-4 4v10H4a4 4 0 00-4 4v12a4 4 0 004 4h10v10a4 4 0 004 4h12a4 4 0 004-4v-10h10a4 4 0 004-4V26zM24 40a16 16 0 110-32 16 16 0 010 32z"></path>
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">Click or drag image here</p>
                                </div>
                            </div>
                        </div>
                      
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition">Save</button>
                            <button type="button" class="bg-gray-500 text-white ml-2 px-4 py-2 rounded-lg shadow hover:bg-gray-600 transition">Cancel</button>
                        </div>
                    </div>
                </div>
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
                img.classList.add('w-full', 'h-full', 'object-cover');
                
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
