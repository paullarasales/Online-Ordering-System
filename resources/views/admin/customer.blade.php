<x-admin-layout>
    <div class="flex justify-center items-center w-full h-screen">
        <div class="w-1/2">
            <h2 class="text-xl font-bold mb-4">Verified Users</h2>
            <table class="table-auto w-full mb-8">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Full Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Email Verified At</th>
                        <th class="px-4 py-2">Verification Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($verifiedUsers as $customer)
                    <tr>
                        <td class="border px-4 py-2">{{ $customer->id }}</td>
                        <td class="border px-4 py-2">{{ $customer->name }}</td>
                        <td class="border px-4 py-2">{{ $customer->email }}</td>
                        <td class="border px-4 py-2">{{ $customer->email_verified_at }}</td>
                        <td class="border px-4 py-2">
                            <span class="text-green-600">Verified</span>
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('admin.view', $customer->id) }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h2 class="text-xl font-bold mb-4">Not Verified Users</h2>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Full Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Email Verified At</th>
                        <th class="px-4 py-2">Verification Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notVerifiedUsers as $customer)
                    <tr>
                        <td class="border px-4 py-2">{{ $customer->id }}</td>
                        <td class="border px-4 py-2">{{ $customer->name }}</td>
                        <td class="border px-4 py-2">{{ $customer->email }}</td>
                        <td class="border px-4 py-2">{{ $customer->email_verified_at }}</td>
                        <td class="border px-4 py-2">
                            @if($customer->verification)
                                <span class="text-red-600">Not Verified</span>
                            @else
                                <span class="text-red-600">Verification Data Missing</span>
                            @endif
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('admin.view', $customer->id) }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
