<x-admin-layout>
    <div class="flex flex-col w-full h-screen items-start justify-center">
        <!--Top-->
        <div class="flex flex-row items-start justify-around w-full h-1/5">
            <!--Total Sales -->
            <div class="flex flex-col justify-center mt-4 items-start p-3 h-5/6 bg-white rounded-md overflow-hidden" style="width: 300px; box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;">
                <h1 class="text-lg font-normal text-gray-400">Total Sales</h1>
                <div class="flex items-center justify-start h-2/3 w-full">
                    <h1 class="text-2xl font-medium">₱50.600.00</h1>
                </div>
                <div class="flex flex-row w-full h-1/4">
                    <span class="text-green-400"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                  </span>
                  <h1 class="text-gray-400 ml-4">in the last month</h1>
                </div>
            </div>
            <div class="flex flex-col justify-center mt-4 items-start p-3 h-5/6 bg-white rounded-md overflow-hidden" style="width: 300px; box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;">
                <h1 class="text-lg font-normal text-gray-500">Total Order</h1>
                <div class="flex items-center justify-start h-2/3 w-full">
                    <h1 class="text-2xl font-medium">1050</h1>
                </div>
                <div class="flex flex-row w-full h-1/4">
                    <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6 9 12.75l4.286-4.286a11.948 11.948 0 0 1 4.306 6.43l.776 2.898m0 0 3.182-5.511m-3.182 5.51-5.511-3.181" />
                    </svg>                      
                  </span>
                  <h1 class="text-gray-500 ml-4">in the last month</h1>
                </div>
            </div>
            <!-- Total Customer -->
            <div class="flex flex-col justify-center mt-4 items-start p-3 h-5/6 bg-white rounded-md overflow-hidden" style="width: 300px; box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;">
                <h1 class="text-lg font-normal text-gray-500">Total Customer</h1>
                <div class="flex items-center justify-start h-2/3 w-full">
                    <h1 class="text-2xl font-medium">4500</h1>
                </div>
                <div class="flex flex-row w-full h-1/4">
                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                    </svg>
                  </span>
                  <h1 class="text-gray-400 ml-4">in the last month</h1>
                </div>
            </div>
        </div>
        <!--Bottom-->
        <h1 class="text-lg font-medium ml-2">Recent Orders</h1>
        <div class="w-full h-4/5">
            @if ($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->contactno }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->address }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->payment_method }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No orders found.</p>
            @endif
        </div>
    </div>
</x-admin-layout>
