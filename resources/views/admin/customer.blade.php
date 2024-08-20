<x-admin-layout>
    <div class="flex flex-col items-center w-full h-screen p-4">
        <div class="w-full bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Customer</h2>
                <div>
                    <button id="show-verified" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">Show Verified</button>
                    <button id="show-not-verified" class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 ml-2">Show Not Verified</button>
                </div>
            </div>

            <div id="verified-users" class="user-section">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Verified Users</h2>
                <table class="table-auto w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="px-4 py-2">Customer</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Email Verified At</th>
                            <th class="px-4 py-2">Verification Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verifiedUsers as $customer)
                        <tr class="hover:bg-gray-50">
                            <td class="flex flex-row items-center justify-start gap-2">
                                <img class="w-10 h-10 rounded-full ml-2 border-solid border-2 border-sky-500"
                                     src="{{ $customer->photo ? asset($customer->photo) : asset('avatar/default.jpeg') }}"
                                alt="Profile Image">
                                {{ $customer->name }}
                            </td>
                            <td class="border px-4 py-2">{{ $customer->email }}</td>
                            <td class="border px-4 py-2">{{ $customer->email_verified_at }}</td>
                            <td class="border px-4 py-2">
                                <span class="text-green-600">Verified</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="not-verified-users" class="user-section hidden">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Not Verified Users</h2>
                <table class="table-auto w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="px-4 py-2">Photo</th>
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
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">
                                <img src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('storage/default-avatar.png') }}" alt="User Photo" class="w-12 h-12 rounded-full object-cover">
                            </td>
                            <td class="border px-4 py-2">{{ $customer->id }}</td>
                            <td class="border px-4 py-2">{{ $customer->name }}</td>
                            <td class="border px-4 py-2">{{ $customer->email }}</td>
                            <td class="border px-4 py-2">{{ $customer->email_verified_at }}</td>
                            <td class="border px-4 py-2">
                                <span class="text-red-600">Not Verified</span>
                            </td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('admin.view', $customer->id) }}" class="bg-blue-600 text-white py-1 px-3 rounded-lg hover:bg-blue-700 transition duration-200">View</a>
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

        async function getUsers() {
            try {
                const response = await fetch('/user-accounts');

                if (!response.ok) {
                    throw new Error('Network response was not ok.')
                }
                
                const data = await response.json();
                console.log('Fetched users', data);
            } catch (error) {
                console.error('Something went wrong when fetching the users', error);
            }
        }

        setInterval(getUsers, 3000);
    </script>
</x-admin-layout>
