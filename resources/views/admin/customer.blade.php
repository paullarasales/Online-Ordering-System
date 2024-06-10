<x-admin-layout>
    <div class="flex justify-center items-center w-full h-screen">
        <table class="table-auto">
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
                @foreach($customers as $customer)
                <tr>
                    <td class="border px-4 py-2">{{ $customer->id }}</td>
                    <td class="border px-4 py-2">{{ $customer->name }}</td>
                    <td class="border px-4 py-2">{{ $customer->email }}</td>
                    <td class="border px-4 py-2">{{ $customer->email_verified_at }}</td>
                    <td class="border px-4 py-2">
                        @if($customer->verification)
                            {{-- Debugging statement --}}
                            {{-- dd($customer->verification); --}}
            
                            @if($customer->verification->verified)
                                <span class="text-green-600">Verified</span>
                            @else
                                <span class="text-red-600">Not Verified</span>
                            @endif
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
</x-admin-layout>