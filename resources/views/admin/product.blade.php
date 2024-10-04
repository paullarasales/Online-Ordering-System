<x-admin-layout>
    <div class="container mx-auto py-4 max-w-5xl">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4">
            <!-- Add Product Button -->
            <div class="mb-4 md:mb-0 flex items-center gap-2">
                <a href="{{ route('product-add-view') }}" class="flex items-center bg-indigo-600 text-white py-2 px-4 rounded-md shadow hover:bg-indigo-700 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                    </svg>
                    {{ __('Add Product') }}
                </a>
            </div>
            <!-- Filtration Section -->
            <div class="flex items-center gap-4">
                <form id="filter-form">
                    <label for="filter" class="text-sm font-medium text-gray-600">Filter by:</label>
                    <select name="filter" id="filter" class="p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500">
                        <option value="all">All</option>
                        <option value="salad">Salad</option>
                        <option value="all day breakfast">All Day Breakfast</option>
                        <option value="pasta">Pasta</option>
                        <option value="all time favorites">All Time Favorites</option>
                        <option value="sandwich burger">Sandwich & Burger</option>
                        <option value="beverages">Beverages</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Product Display Section -->
        <div id="product-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @if($products->isEmpty())
                <div class="col-span-full text-center text-gray-600">No products to display.</div>
            @else
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img src="{{ asset($product->photo) }}" alt="Product Image" class="w-full h-32 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->product_name }}</h3>
                            <p class="text-sm text-gray-600">Price: ₱{{ $product->price }}</p>
                            <p class="text-sm text-gray-600 mb-2">Description: {{ $product->description }}</p>
                            <p class="text-sm text-gray-600 mb-4">Stock Quantity: {{ $product->stockQuantity }}</p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('update-view', $product->id)}}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                <form action="{{ route('product.destroy', $product->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Pagination Section -->
        <div class="mt-6">
            {{ $products->links('vendor.pagination.tailwind') }}
        </div>
    </div>
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterSelect = document.getElementById('filter');
        const productList = document.getElementById('product-list');

        filterSelect.addEventListener('change', function() {
            const selectedFilter = this.value;

            fetch(`{{ route('product.filter') }}?filter=${selectedFilter}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                updateProductList(data.products);
            })
            .catch(error => console.error('Error:', error));
        });

        function updateProductList(products) {
            productList.innerHTML = '';

            if (products.length === 0) {
                productList.innerHTML = '<div class="col-span-full text-center text-gray-600">No products to display.</div>';
            } else {
                products.forEach(product => {
                    const productElement = createProductElement(product);
                    productList.appendChild(productElement);
                });
            }
        }

        function createProductElement(product) {
            const div = document.createElement('div');
            div.className = 'bg-white rounded-lg shadow-lg overflow-hidden';
            div.innerHTML = `
                <img src="${product.photo}" alt="Product Image" class="w-full h-32 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">${product.product_name}</h3>
                    <p class="text-sm text-gray-600">Price: ₱${product.price}</p>
                    <p class="text-sm text-gray-600 mb-2">Description: ${product.description}</p>
                    <p class="text-sm text-gray-600 mb-4">Stock Quantity: ${product.stockQuantity}</p>
                    <div class="flex justify-between items-center">
                        <a href="/admin/product/update/${product.id}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                        <form action="/admin/product/${product.id}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </div>
                </div>
            `;
            return div;
        }
    });
    </script>
    @endpush
</x-admin-layout>

