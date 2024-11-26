<x-admin-layout>
    <div class="flex justify-center items-center h-screen bg-gray-100">
        <div class="w-full max-w-4xl bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-xl font-semibold mb-4">Edit Product</h1>
            <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Use PUT method for updating records -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left container for product information -->
                    <div>
                        <div class="mb-4">
                            <label for="productName" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" id="productName" name="product_name" value="{{ $product->product_name }}" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="text" id="price" name="price" value="{{ $product->price }}" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="3" class="mt-1 p-2 block w-full border-gray-300 rounded-md">{{ $product->description }}</textarea>
                        </div>
                        <!-- Dropdown for selecting category -->
                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select id="category" name="category_id" class="mt-1 p-2 block w-full border-gray-300 rounded-md">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @if($product->category_id == $category->id) selected @endif>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Right container for product image -->
                    <div>
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                            <div class="flex items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-md relative">
                                <input type="file" id="image" name="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage()">
                                <div id="imagePreview" class="absolute inset-0 w-full h-full overflow-hidden">
                                    <img src="{{ asset($product->photo) }}" alt="Product Image" class="w-full h-auto">
                                </div>
                                <div class="text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path d="M44 22a4 4 0 00-4-4h-8V8a4 4 0 00-4-4H16a4 4 0 00-4 4v10H4a4 4 0 00-4 4v12a4 4 0 004 4h10v10a4 4 0 004 4h12a4 4 0 004-4v-10h10a4 4 0 004-4V26zM24 40a16 16 0 110-32 16 16 0 010 32z"></path>
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">Click or drag image here</p>
                                </div>
                            </div>
                        </div>
                        <!-- Buttons within a dotted container with margin -->
                        <div class="flex justify-end mt-6">
                            <div class="border-dotted border-gray-400 p-4 rounded-md">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update</button>
                                <a href="{{ route('product') }}" class="bg-gray-500 text-white ml-2 px-4 py-2 rounded-md hover:bg-gray-600">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage() {
            const fileInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            
            const file = fileInput.files[0];
            const reader = new FileReader();
            
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.classList.add('w-full', 'h-auto');
                
                imagePreview.innerHTML = '';
                imagePreview.appendChild(img);
            };
            
            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-admin-layout>