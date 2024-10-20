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
                <h2 class="text-3xl font-medium text-gray-900">Customer</h2>
                <div class="flex space-x-3 items-center">
                    <input type="text" id="search" placeholder="Search verified users" class="py-2 px-4 border rounded-md">
                    <button id="search-btn" class="py-2 px-4 bg-green-500 text-white rounded-md">Search</button>
                    <button id="show-verified" class="py-2 px-4 bg-blue-500 text-white rounded-md">Show Verified</button>
                    <button id="show-not-verified" class="py-2 px-4 bg-red-500 text-white rounded-md">Show Not Verified</button>
                </div>
            </div>

            <div id="verified-users" class="user-section">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-3 text-left">Customer</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Email Verified At</th>
                            <th class="px-4 py-3 text-left">Verification Status</th>
                            <th class="px-4 py-3 text-left">Blocked Until</th> <!-- New Column -->
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="verified-users-tbody">
                        @foreach($verifiedUsers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->email_verified_at }}</td>
                            <td>Verified</td>
                            <td>
                                @if($customer->is_blocked && $customer->blocked_until)
                                    <span class="text-red-500">Blocked until: {{ $customer->blocked_until->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(!$customer->is_blocked)
                                    <a href="{{ route('admin.block', $customer->id) }}"
                                       class="bg-red-500 text-white py-2 px-4 rounded shadow hover:bg-red-600 transition-all">
                                       Block
                                    </a>
                                @else
                                    <span class="text-red-500">Blocked</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="not-verified-users" class="user-section hidden">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700">
                            <th class="px-4 py-3 text-left">Customer</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Email Verified At</th>
                            <th class="px-4 py-3 text-left">Verification Status</th>
                            <th class="px-4 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="not-verified-users-tbody">
                        @foreach($notVerifiedUsers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->email_verified_at }}</td>
                            <td>Not Verified</td>
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
            const verifiedUsersSection = document.getElementById('verified-users');
            const notVerifiedUsersSection = document.getElementById('not-verified-users');
            const searchInput = document.getElementById('search');
            const searchBtn = document.getElementById('search-btn');
            const verifiedUsersTbody = document.getElementById('verified-users-tbody');

            const originalVerifiedUsersHTML = verifiedUsersTbody.innerHTML;

            document.getElementById('show-verified').addEventListener('click', function () {
                verifiedUsersSection.classList.remove('hidden');
                notVerifiedUsersSection.classList.add('hidden');
                verifiedUsersTbody.innerHTML = originalVerifiedUsersHTML;
            });

            document.getElementById('show-not-verified').addEventListener('click', function () {
                verifiedUsersSection.classList.add('hidden');
                notVerifiedUsersSection.classList.remove('hidden');
            });

            searchBtn.addEventListener('click', function () {
                const query = searchInput.value.trim();
                if (query === '') {
                    verifiedUsersTbody.innerHTML = '';
                    return;
                }

                fetch(`/user-search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        verifiedUsersTbody.innerHTML = '';
                        if (data.results.length === 0) {
                            verifiedUsersTbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">No results found</td></tr>';
                        } else {
                            data.results.forEach(user => {
                                const row = `
                                    <tr>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.email_verified_at}</td>
                                        <td>${user.verification_status}</td>
                                        <td>
                                            @if($customer->is_blocked && $customer->blocked_until)
                                                <span class="text-red-500">Blocked until: {{ $customer->blocked_until->diffForHumans() }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if(!$customer->is_blocked)
                                                <a href="{{ route('admin.block', $customer->id) }}"
                                                class="bg-red-500 text-white py-2 px-4 rounded shadow hover:bg-red-600 transition-all">
                                                Block
                                                </a>
                                            @else
                                                <span class="text-red-500">Blocked</span>
                                            @endif
                                        </td>
                                    </tr>
                                `;
                                verifiedUsersTbody.innerHTML += row;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        verifiedUsersTbody.innerHTML = '<tr><td colspan="4" class="text-center py-4">An error occurred while searching</td></tr>';
                    });
            });
        });
    </script>
</x-admin-layout>
