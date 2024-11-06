<x-admin-layout>
    <style>
        .input-wide {
            width: 800px;
            height: 2.58rem;
        }
        .button {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
        }
        .button:hover {
            opacity: 0.85;
        }
        .btn-search {
            background-color: #38a169; /* Green */
            color: white;
        }
        .btn-show-verified {
            background-color: #3182ce; /* Blue */
            color: white;
        }
        .btn-show-not-verified {
            background-color: #e53e3e; /* Red */
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            font-weight: bold;
            text-align: left;
            background-color: #edf2f7; /* Light Gray */
            padding: 0.75rem;
        }
        td {
            padding: 0.75rem;
            border-top: 1px solid #e2e8f0; /* Light Border */
        }
        tr:nth-child(even) {
            background-color: #f7fafc; /* Light Background for even rows */
        }
        .text-red-500 {
            color: #e53e3e; /* Red text */
        }
        .shadow-lg {
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="flex flex-col items-center w-full h-screen p-4 bg-gray-100">
        <div class="w-full max-w-6xl bg-white rounded-lg shadow-lg p-6 h-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-medium text-gray-900">Customer</h2>
                <div class="flex space-x-3 items-center">
                    <input type="text" id="search" placeholder="Search verified users" class="py-2 px-4 border rounded-md">
                    <button id="search-btn" class="button btn-search">Search</button>
                    <button id="show-verified" class="button btn-show-verified">Show Verified</button>
                    <button id="show-not-verified" class="button btn-show-not-verified">Show Not Verified</button>
                </div>
            </div>

            <div id="verified-users" class="user-section">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Verification Status</th>
                            <th>Blocked Until</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="verified-users-tbody">
                        @foreach($verifiedUsers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>Verified</td>
                            <td>
                                @if($customer->is_blocked && $customer->blocked_until)
                                    <span class="text-red-500">Blocked until: {{ $customer->blocked_until->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td>
                                @if(!$customer->is_blocked)
                                    <a href="{{ route('admin.block', $customer->id) }}" class="button btn-show-not-verified">Block</a>
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
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Email Verified At</th>
                            <th>Verification Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="not-verified-users-tbody">
                        @foreach($notVerifiedUsers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->email_verified_at }}</td>
                            <td>Not Verified</td>
                            <td>
                                <a href="{{ route('admin.view', $customer->id) }}" class="button btn-show-verified">View</a>
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
                            verifiedUsersTbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">No results found</td></tr>';
                        } else {
                            data.results.forEach(user => {
                                const row = `
                                    <tr>
                                        <td>${user.name}</td>
                                        <td>${user.email}</td>
                                        <td>${user.email_verified_at || 'Not Verified'}</td>
                                        <td>${user.verification_status || 'N/A'}</td>
                                        <td>
                                            @if($customer->is_blocked && $customer->blocked_until)
                                                <span class="text-red-500">Blocked until: {{ $customer->blocked_until->diffForHumans() }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$customer->is_blocked)
                                                <a href="{{ route('admin.block', $customer->id) }}" class="button btn-show-not-verified">Block</a>
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
                        verifiedUsersTbody.innerHTML = '<tr><td colspan="5" class="text-center py-4">An error occurred while searching</td></tr>';
                    });
            });
        });
    </script>
</x-admin-layout>
