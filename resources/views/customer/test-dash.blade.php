<x-app-layout>
    <!-- Notification Banner -->
    @if(session('success'))
        <div id="notification-banner" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded-md">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div id="notification-banner" class="fixed top-0 right-0 m-4 p-4 bg-red-500 text-white rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main content -->
    <div class="container mx-auto py-4 max-w-5xl">
        <!-- Filtration Section -->
        <div class="flex items-center gap-4">
            <form id="filter-form">
                <label for="filter" class="text-sm font-medium text-gray-6000">Filter by:</label>
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

        <!-- Product Display Section -->
        <div id="product-list" class="grid grid-cols-2 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
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
                                </div>
                                <form action="{{ route('add-to-cart', ['productId' => $product->id]) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Add to Cart</button>
                                </form>
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
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notificationBanner = document.getElementById('notification-banner');
            const filterSelect = document.getElementById('filter');
            const productList = document.getElementById('product-list');

            filterSelect.addEventListener('change', function () {
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
                    console.log('Filtered Products: ', data)
                    updateProductList(data.products)
                })
                .catch(error => console.error('Error', error));
            });

            function updateProductList(products) {
                productList.innerHTML = '';

                if (products.length === 0) {
                    productList.innerHTML = '<div class="col-span-full text-center text-gray-600">No Products to display.</div>'
                } else {
                    products.forEach(product => {
                        const productElement = createProductElement(product);
                        productList.appendChild(productElement);
                    })
                }
            }

            function createProductElement(product) {
                const div = document.createElement('div');
                div.className = 'bg-white rouded-lg shadow-lg overflow-hidden';
                div.innerHTML = `
                    <img src="${product.photo}" alt="Product Image" class="w-full h-32 object-cover">
                    <div class="p-4">
                        <h2>${product.product_name}</h2>
                    </div>
                `;
            }

            if (notificationBanner) {
                setTimeout(() => {
                    notificationBanner.style.opacity = '0';
                    setTimeout(() => {
                        notificationBanner.style.display = 'none';
                    }, 5000);
                }, 3000);
            }
        });
    </script>
    @endpush
</x-app-layout>