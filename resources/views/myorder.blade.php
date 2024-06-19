<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @forelse ($orders as $order)
                    <div class="mb-8">
                        <h3 class="font-semibold text-lg">{{ __('Order ID: ') }}{{ $order->id }}</h3>
                        <p>{{ __('Order Date: ') }}{{ $order->created_at->format('Y-m-d') }}</p>
                        <p>{{ __('Status: ') }}{{ $order->status }}</p>
                        <p>{{ __('Total: ') }}${{ number_format($order->total, 2) }}</p>

                        <table class="min-w-full leading-normal mt-4">
                            <thead>
                                <tr>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Image
                                    </th>
                                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->items as $item)
                                    <tr>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $item->product->product_name }}
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            <img src="{{ asset($item->product->photo) }}" alt="{{ $item->product->product_name }}" class="w-16 h-16">
                                        </td>
                                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            {{ $item->quantity }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                            No items found for this order.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div class="mb-8">
                        <p>{{ __('You have no orders.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
