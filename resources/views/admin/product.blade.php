<x-admin-layout>
    <div class="container mx-auto py-4 max-w-5xl">
        <!-- Filtration Section -->
        <div class="flex flex-row md:flex-row justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800 md:ml-4 mb-2 md:mb-0">Products</h2>
            <!-- Add your filtration options here -->
            <div class="flex items-center md:mr-4 mb-2 md:mb-0">
                <label for="filter" class="text-sm font-medium text-gray-600 mr-2">Filter by:</label>
                <select id="filter" name="filter" class="mt-1 p-2 border-gray-300 rounded-md">
                    <option value="all">All</option>
                    <option value="salad">Salad</option>
                    <option value="all-day-breakfast">All Day Breakfast</option>
                    <option value="pasta">Pasta</option>
                    <option value="favorites">All Time Favorites</option>
                    <option value="sandwich-burger">Sandwich & Burger</option>
                </select>
            </div>
        </div>
        
        <!-- Product Display Section -->
        <div class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @if($products->isEmpty())
                <div class="text-center text-gray-600">No products to display.</div>
            @else
                @foreach($products as $product)
                    <div class="w-full sm:w-1/2 md:w-auto md:h-86 lg:w-54 mb-4 flex">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden w-full flex flex-col justify-between">
                            <img src="{{ asset($product->photo) }}" alt="Product Image" class="w-full h-32 md:h-28 object-cover sm:h-32">
                            <div class="p-4 flex flex-col justify-between flex-grow">
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                                    <p class="text-sm text-gray-600">Price: ₱{{ $product->price }}</p>
                                    <p class="text-sm text-gray-600">Description: {{ $product->description }}</p>
                                    <p class="text-sm text-gray-600">Stock Quantity: {{ $product->stockQuantity }}</p>
                                </div>
                                <div class="mt-auto flex items-center justify-between">
                                    <a href="{{ route('update-view', $product->id)}}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                    <form action="{{ route('product.destroy', $product->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>        
        <!-- Pagination Section --> 
        <div class="pagination">
            {{ $products->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</x-admin-layout>
