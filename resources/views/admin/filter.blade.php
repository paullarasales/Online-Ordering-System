<x-admin-layout>
    <!-- Go Back Button -->
    <div class="mb-4">
        <a href="{{ route('product') }}" class="flex items-center text-indigo-600 hover:text-indigo-800">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                <path fill-rule="evenodd" d="M2.293 12.293a1 1 0 0 1 0-1.414L7.586 5.5a1 1 0 0 1 1.414 1.414L4.414 11H21a1 1 0 1 1 0 2H4.414l4.586 4.586a1 1 0 0 1-1.414 1.414l-5.293-5.293z" clip-rule="evenodd" />
            </svg>
            Go Back
        </a>
    </div>

    <!-- Product Display Section -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
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
</x-admin-layout>
