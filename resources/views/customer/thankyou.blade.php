<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12 bg-white shadow-md rounded-lg">
        <h1 class="text-4xl font-extrabold text-center text-gray-800 mb-6">Thank You for Your Order!</h1>

        <!-- Order Summary -->
        <div class="bg-gray-50 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Order Details</h2>
            <p class="text-lg text-gray-600 mb-2"><strong>Order ID:</strong> {{ $order->id }}</p>
            <p class="text-lg text-gray-600 mb-2"><strong>Address:</strong> {{ $order->address }}</p>
            <p class="text-lg text-gray-600 mb-2"><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
        </div>

        <!-- Order Items -->
        <div class="mt-8 bg-gray-50 p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Order Items</h2>
            <ul class="divide-y divide-gray-200">
                @foreach ($order->items as $item)
                    <li class="flex justify-between items-center py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="w-16 h-16 object-cover rounded-lg" src="{{ asset(optional($item->product)->photo) }}" alt="{{ optional($item->product)->product_name }}">
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">{{ optional($item->product)->product_name }}</h3>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <p class="text-lg font-semibold text-gray-700">₱{{ number_format(optional($item->product)->price * $item->quantity + 60, 2) }}</p>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-8 bg-gray-50 p-6 rounded-lg shadow-md">
            <form action="{{ route('send-receipt', $order->id) }}" method="POST">
                @csrf
                <label class="inline-flex items-center">
                    <input type="checkbox" name="send_receipt" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Send me a receipt by email</span>
                </label>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out mt-4">
                    Submit
                </button>
            </form>
        </div>
        
        <div class="mt-12 text-center">
            <a href="{{ route('userdashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out">
                Close
            </a>
        </div>
    </div>
</x-app-layout>
