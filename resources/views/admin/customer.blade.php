<x-admin-layout>
    <style>
        .input-wide {
            width: 800px;
            height: 2.58rem;
        }
    </style>
    <div class="flex flex-col items-center w-full h-screen p-4 bg-gray-100">
        <div class="w-full max-w-6xl bg-white rounded-lg shadow-xl p-6 h-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-normal text-gray-900">Customer</h2>
                <div class="flex space-x-3 items-center">
                    <form action="{{ route('user.search') }}" method="GET" class="relative flex items-center w-full max-w-lg">
                        <input type="text" name="query" class="input-wide block px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 pl-10 sm:text-sm w-full" placeholder="Type to search customer">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>                              
                        </div>
                    </form>
                    <select id="user-status" class="ml-4 py-2 px-4 border border-gray-300 rounded-md bg-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="verified">Show Verified</option>
                        <option value="not-verified">Show Not Verified</option>
                    </select>
                </div>
            </div>

            <div id="verified-users" class="user-section">
                <!-- Loading Spinner -->
                <div class="loading-spinner hidden flex justify-center items-center py-5">
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
            const userStatusDropdown = document.getElementById('user-status');
            const verifiedUsersSection = document.getElementById('verified-users');
            const notVerifiedUsersSection = document.getElementById('not-verified-users');

            userStatusDropdown.addEventListener('change', function () {
                if (this.value === 'verified') {
                    verifiedUsersSection.classList.remove('hidden');
                    notVerifiedUsersSection.classList.add('hidden');
                } else {
                    verifiedUsersSection.classList.add('hidden');
                    notVerifiedUsersSection.classList.remove('hidden');
                }
            });

            document.querySelector('input[name="query"]').addEventListener('input', async function () {
                const query = this.value;

                if (query.trim() === "") {
                    clearTable();
                    try {
                        const response = await fetch('/user-search');
                        if (!response.ok) {
                            throw new Error('Network response was not ok.');
                        }
                        const data = await response.json();
                        updateTable(data.results);
                    } catch (error) {
                        console.error('Error fetching results:', error);
                    }
                    return;
                }
                
                document.querySelector('.loading-spinner').classList.remove('hidden');
                
                try {
                    const response = await fetch(`/user-search?query=${encodeURIComponent(query)}`);
                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }

                    const data = await response.json();
                    updateTable(data.results);
                } catch (error) {
                    console.error('Error fetching results:', error);
                } finally {
                    document.querySelector('.loading-spinner').classList.add('hidden');
                }
            });

            function updateTable(data) {
                const verifiedSectionVisible = !verifiedUsersSection.classList.contains('hidden');

                const tableBody = verifiedSectionVisible 
                    ? document.querySelector('#verified-users tbody')
                    : document.querySelector('#not-verified-users tbody');

                tableBody.innerHTML = '';

                data.forEach(customer => {
                    const row = `
                        <tr class="hover:bg-gray-50 border-b transition-colors duration-150">
                            <td class="flex items-center gap-4 px-4 py-3">
                                <img class="w-12 h-12 rounded-full border border-sky-500"
                                    src="${customer.photo ? customer.photo : 'avatar/default.jpeg'}"
                                    alt="Profile Image">
                                <span class="text-gray-800">${customer.name}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">${customer.email}</td>
                            <td class="px-4 py-3 text-gray-600">${customer.email_verified_at ? new Date(customer.email_verified_at).toLocaleString() : ''}</td>
                            <td class="px-4 py-3 ${verifiedSectionVisible ? 'text-green-600' : 'text-red-600'}">
                                ${verifiedSectionVisible ? 'Verified' : 'Not Verified'}
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            }

            function clearTable() {
                const tableBody = document.querySelector('#verified-users tbody');
                tableBody.innerHTML = '';
            }
        });
    </script>
</x-admin-layout>
