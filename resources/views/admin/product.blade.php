<x-admin-layout>
    <div class="container mx-auto p-3 max-w-7xl">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <!-- Add Product Button -->
            <a href="{{ route('product-add-view') }}" class="bg-indigo-600 text-white py-2 px-4 rounded-md shadow hover:bg-indigo-700 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 inline mr-2">
                    <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                    <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                </svg>
                {{ __('Add Product') }}
            </a>

            <!-- Filter Section -->
            <div>
                <form id="filter-form">
                    <label for="filter" class="mr-2 text-sm font-medium text-gray-700">Filter by:</label>
                    <select id="filter" name="filter" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500">
                        <option value="all" class="font-semibold">All</option>
                        <option value="salad" class="font-semibold">Salad</option>
                        <option value="all day breakfast" class="font-semibold">All Day Breakfast</option>
                        <option value="pasta" class="font-semibold">Pasta</option>
                        <option value="all time favorites" class="font-semibold">All Time Favorites</option>
                        <option value="sandwich burger" class="font-semibold">Sandwich & Burger</option>
                        <option value="beverages" class="font-semibold">Beverages</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Product Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr class="">
                        <th class="py-4 px-4 text-left text-sm font-semibold text-gray-700">Product</th>
                        <th class="py-4 px-4 text-left text-sm font-semibold text-gray-700">Category</th>
                        <th class="py-4 px-4 text-left text-sm font-semibold text-gray-700">Price</th>
                        <th class="py-4 px-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->isEmpty())
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-600">No products to display.</td>
                        </tr>
                    @else
                        @foreach($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4 px-4 text-gray-700">
                                <div class="flex items-center">
                                    <img src="{{ asset($product->photo) }}" alt="Product Image" class="w-16 h-16 object-cover rounded-md mr-4">
                                    <span class="text-gray-700 font-semibold">{{ $product->product_name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-gray-700 font-semibold">{{ $product->category->category_name }}</td>
                            <td class="py-4 px-4 text-gray-700 font-semibold">₱{{ number_format($product->price, 2) }}</td>
                            <td class="py-4 px-4 flex space-x-2 mt-5">
                            <button class="w-20 h-10 bg-blue-500 text-white rounded hover:bg-blue-600">
                                <a href="{{ route('update-view', $product->id) }}" class="w-full h-full flex items-center justify-center">
                                    Edit
                                </a>
                            </button>
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 bg-red-500 text-white rounded hover:bg-red-600 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9L14.4 18m-4.788 0L9.26 9M9.26 9H14.74M19.072 5.79c.342.052.682.107 1.022.166m-1.022-.166L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </td>

                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
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
            filterSelect.addEventListener('change', function() {
                const selectedFilter = this.value;

                const url = selectedFilter === 'All' ? '{{ route('product') }}' : `{{ route('product.filter') }}?filter=${selectedFilter}`;

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Update product list dynamically
                    updateProductTable(data.products);
                })
                .catch(error => console.error('Error:', error));
            });

            function updateProductTable(products) {
                const tbody = document.querySelector('tbody');
                tbody.innerHTML = '';

                if (products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-600">No products to display.</td></tr>';
                } else {
                    products.forEach(product => {
                        const row = createProductRow(product);
                        tbody.appendChild(row);
                    });
                }
            }

            function createProductRow(product) {
                const tr = document.createElement('tr');
                tr.className = 'border-b hover:bg-gray-50';
                tr.innerHTML = `
                    <td class="py-4 px-4 text-gray-700">
                        <div class="flex items-center">
                            <img src="${product.photo}" alt="Product Image" class="w-16 h-16 object-cover rounded-md mr-4">
                            <span>${product.product_name}</span>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-gray-700 font-semibold">${product.category_name}</td>
                    <td class="py-4 px-4 text-gray-700 font-semibold">₱${product.price.toFixed(2)}</td>
                    <td class="py-4 px-4 flex space-x-2">
                        <a href="/admin/product/update/${product.id}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                        <form action="/admin/product/${product.id}" method="POST">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                `;
                return tr;
            }
        });
    </script>
    @endpush
</x-admin-layout>
