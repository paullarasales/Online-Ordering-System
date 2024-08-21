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
               <!-- Search Bar -->
                <div class="hidden sm:flex sm:items-center sm:ms-6 w-full mb-5">
                    <form action="{{ route('user.search') }}" method="GET" class="relative flex items-center">
                        <input type="text" name="query" class="block px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 pl-10 sm:text-sm" placeholder="Search" style="width: 600px;">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.293 11.293a1 1 0 011.414 1.414l-2.5 2.5a1 1 0 01-1.414 0 1 1 0 01-.074-1.327l2.5-2.5zM8 13a5 5 0 100-10 5 5 0 000 10z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                    </form>
                </div>
                <div class="loading-spinner" class="hidden flex justify-center items-center py-5">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span class="ml-2 text-blue-500">Loading...</span>
                </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('show-verified').addEventListener('click', function () {
                document.getElementById('verified-users').classList.remove('hidden');
                document.getElementById('not-verified-users').classList.add('hidden');
            });

            document.getElementById('show-not-verified').addEventListener('click', function () {
                document.getElementById('verified-users').classList.add('hidden');
                document.getElementById('not-verified-users').classList.remove('hidden');
            });

            document.querySelector('input[name="query"]').addEventListener('input', async function () {
                const query = this.value;

                const response = await fetch(`/user-search?query=${query}`);
                const data = await response.json();

                document.getElementById('loading-spinner').classList.add('hidden');
                updateTable(data.results);
            });

            function updateTable(data) {
                const verifiedSectionVisible = !document.getElementById('verified-users').classList.contains('hidden');

                const tableBody = verifiedSectionVisible 
                    ? document.querySelector('#verified-users tbody')
                    : document.querySelector('#not-verified-users tbody');

                tableBody.innerHTML = '';

                data.forEach(customer => {
                    const row = `
                        <tr class=hover:bg-gray-50 border-b transition-colors duration-150">
                            <td class="flex items-center gap-4 px-4 py-3">
                                <img class="w-12 h-12 rounded-full border border-sky-500"
                                    src="${customer.photo ? customer.photo : 'avatar/default.jpeg'}"
                                    alt="Profile Image">
                                <span class="text-gray-800">${customer.name}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">${customer.email}</td>
                            <td class="px-4 py-3 text-gray-600">${customer.email_verified_at}</td>
                            <td class="px-4 py-3 ${verifiedSectionVisible ? 'text-green-600' : 'text-red-600'}">
                                ${verifiedSectionVisible ? 'Verified' : 'Not Verified'}
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            }

            function clearTable() {
                const tableBody = document.querySelector('#verified-users tbody') || document.querySelector('#not-verified-users tbody');
                table 
            }
        });
    </script>
</x-admin-layout>
