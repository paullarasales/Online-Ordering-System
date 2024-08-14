<x-admin-layout>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Order Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Details of the order.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $order->id }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $order->user->name }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $order->address }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">{{ $order->payment_method }}</dd>
                </div>
               <!-- Order Items -->
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Ordered Products</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <!-- Display order items -->
<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
    <dt class="text-sm font-medium text-gray-500">Ordered Products</dt>
    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2">
        <ul class="divide-y divide-gray-200">
            @forelse ($order->items as $item)
            <li class="flex items-center py-2">
                <div class="flex-1">
                @if ($item->product)
                        <img src="{{ asset($item->product->photo)}}" class="h-20 w-20">
                        <h3 class="text-lg font-semibold">{{ $item->product->product_name }}</h3>
                        <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                    @else
                        <p class="text-red-600">Product not found</p>
                    @endif
                </div>
                @if ($item->product)
                    <p class="text-gray-600">Price: ₱{{ $item->product->price }}</p>
                @endif
            </li>
        @empty
            <li>No items found</li>
        @endforelse

        </ul>
    </dd>
</div>

            </dl>
        </div>
            </dl>
        </div>
    </div>
</x-admin-layout>
