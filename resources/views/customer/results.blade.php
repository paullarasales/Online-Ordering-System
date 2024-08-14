<x-app-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Search Results</h1>

        @if($results->isEmpty())
            <p>No results found.</p>
        @else
            <div class="flex flex-wrap -m-4">
                @foreach($results as $result)
                <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 p-4">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ asset($result->photo) }}" alt="Product Image" class="w-full h-32 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $result->product_name }}</h3>
                            <p class="text-sm text-gray-600">Price: ₱{{ $result->price }}</p>
                            <p class="text-sm text-gray-600">Description: {{ $result->description }}</p>
                            <form action="{{ route('add-to-cart', ['productId' => $result->id]) }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $result->id }}">
                                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-md">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
