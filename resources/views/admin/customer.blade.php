<x-admin-layout>
    <div class="flex flex-col items-center w-full h-screen p-4 bg-gray-100">
        <div class="w-full max-w-6xl bg-white rounded-lg shadow-xl p-6 h-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-normal text-gray-900">Customer</h2>
                <div class="space-x-3">
                    <button id="show-verified" class="bg-blue-500 text-white py-2 px-5 rounded-lg shadow hover:bg-blue-600 transition-all">Show Verified</button>
                    <button id="show-not-verified" class="bg-gray-500 text-white py-2 px-5 rounded-lg shadow hover:bg-gray-600 transition-all">Show Not Verified</button>
                </div>
            </div>

            <div id="verified-users" class="user-section">
                <h3 class="text-xl font-normal text-gray-800 mb-5 border-b pb-2">Verified Users</h3>
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-3 text-left">Customer</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Email Verified At</th>
                            <th class="px-4 py-3 text-left">Verification Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verifiedUsers as $customer)
                        <tr class="hover:bg-gray-50 border-b transition-colors duration-150">
                            <td class="flex items-center gap-4 px-4 py-3">
                                <img class="w-12 h-12 rounded-full border border-sky-500"
                                     src="{{ $customer->photo ? asset($customer->photo) : asset('avatar/default.jpeg') }}"
                                     alt="Profile Image">
                                <span class="text-gray-800">{{ $customer->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->email }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->email_verified_at }}</td>
                            <td class="px-4 py-3 text-green-600">Verified</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="not-verified-users" class="user-section hidden">
                <h3 class="text-xl font-normal text-gray-800 mb-5 border-b pb-2">Not Verified Users</h3>
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-3 text-left">Photo</th>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Full Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Email Verified At</th>
                            <th class="px-4 py-3 text-left">Verification Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notVerifiedUsers as $customer)
                        <tr class="hover:bg-gray-50 border-b transition-colors duration-150">
                            <td class="px-4 py-3">
                                <img src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('storage/default-avatar.png') }}" alt="User Photo" class="w-12 h-12 rounded-full object-cover">
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->id }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $customer->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->email }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $customer->email_verified_at }}</td>
                            <td class="px-4 py-3 text-red-600">Not Verified</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.view', $customer->id) }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition-all">View</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('show-verified').addEventListener('click', function () {
            document.getElementById('verified-users').classList.remove('hidden');
            document.getElementById('not-verified-users').classList.add('hidden');
        });

        document.getElementById('show-not-verified').addEventListener('click', function () {
            document.getElementById('verified-users').classList.add('hidden');
            document.getElementById('not-verified-users').classList.remove('hidden');
        });
    </script>
</x-admin-layout>
