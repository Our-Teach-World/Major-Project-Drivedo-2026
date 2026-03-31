<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - EduShare Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #000000;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: 2px solid #000000;
            background-color: #ffffff;
            color: #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-primary {
            background-color: #000000;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .btn:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .btn-success {
            background-color: #2e7d32;
            color: #ffffff;
            border: 2px solid #2e7d32;
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .btn-success:hover {
            background-color: #1b5e20;
        }

        .btn-danger {
            background-color: #c62828;
            color: #ffffff;
            border: 2px solid #c62828;
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .btn-danger:hover {
            background-color: #ad1457;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        .actions {
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 2px solid;
        }

        .alert-success {
            background-color: #e8f5e9;
            border-color: #2e7d32;
            color: #2e7d32;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border: 2px solid #000000;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #000000;
            color: #ffffff;
            padding: 15px;
            text-align: left;
            font-weight: 700;
            border-bottom: 2px solid #000000;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-block;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: 600;
            display: inline-block;
        }

        .actions-cell {
            display: flex;
            gap: 10px;
        }

        .pagination {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 2px solid #000000;
            border-radius: 5px;
            text-decoration: none;
            color: #000000;
            font-weight: 600;
        }

        .pagination a:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .pagination .active {
            background-color: #000000;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 10px;
            }

            .actions-cell {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">⚙️ Manage Users</div>
        <div class="navbar-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn">Back to Dashboard</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>User Management</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="margin-bottom: 20px;">
            <input type="text" id="searchInput" placeholder="🔍 Search users by name, role, or status..." 
                class="btn" style="width: 100%; max-width: 400px; padding: 10px; border: 2px solid #000000; background-color: #ffffff; color: #000000;">
        </div>

        <div class="actions">
            <button onclick="bulkAction('approve')" class="btn btn-success">✓ Approve Selected</button>
            <button onclick="bulkAction('delete')" class="btn btn-danger">🗑 Delete Selected</button>
            <a href="{{ route('admin.add-user') }}" class="btn btn-primary">➕ Add New User</a>
            <a href="{{ route('admin.export') }}" class="btn btn-primary">📥 Export to CSV</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;"><input type="checkbox" id="selectAll" style="cursor: pointer;"></th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse ($users as $user)
                    <tr>
                        <td><input type="checkbox" class="select-user" value="{{ $user->id }}" style="cursor: pointer;"></td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>••••••••</td>
                        <td>{{ $user->created_at->format('d-m-Y H:i A') }}</td>
                        <td>
                            @if ($user->status === 'pending')
                                <span class="status-pending">Pending</span>
                            @elseif ($user->status === 'approved')
                                <span class="status-approved">✔ Approved</span>
                            @else
                                <span class="status-pending">{{ ucfirst($user->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                @if ($user->status === 'pending')
                                    <button onclick="approveUser({{ $user->id }})" class="btn btn-success">Approve</button>
                                @else
                                    <span style="color: #4ade80; font-weight: 600;">✔ Approved</span>
                                @endif
                                <button onclick="deleteUser({{ $user->id }})" class="btn btn-danger">Delete</button>
                                <a href="{{ route('admin.edit-user', $user->id) }}" class="btn" style="background-color: #3b82f6; color: #ffffff; border-color: #3b82f6;">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px;">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($users->hasPages())
            <div class="pagination">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script>
        const renderUserRow = (user) => {
            const statusClass = user.status === 'approved' ? 'status-approved' : 'status-pending';
            const statusLabel = user.status === 'approved' ? '✔ Approved' : 'Pending';
            return `
                <tr>
                    <td><input type="checkbox" class="select-user" value="${user.id}" style="cursor: pointer;"></td>
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</td>
                    <td>••••••••</td>
                    <td>${new Date(user.created_at).toLocaleString()}</td>
                    <td><span class="${statusClass}">${statusLabel}</span></td>
                    <td>
                        <div class="actions-cell">
                            ${user.status === 'pending' ? `<button onclick="approveUser(${user.id})" class="btn btn-success">Approve</button>` : '<span style="color: #4ade80; font-weight: 600;">✔ Approved</span>'}
                            <button onclick="deleteUser(${user.id})" class="btn btn-danger">Delete</button>
                            <a href="${window.location.origin + '/admin/edit-user/' + user.id}" class="btn" style="background-color: #3b82f6; color: #ffffff; border-color: #3b82f6;">Edit</a>
                        </div>
                    </td>
                </tr>
            `;
        };

        const fetchUsers = () => {
            const q = document.getElementById('searchInput').value;
            fetch(`{{ route('admin.users') }}?ajax=1&q=${encodeURIComponent(q)}`)
                .then(res => res.json())
                .then(users => {
                    const html = users.map(renderUserRow).join('');
                    document.getElementById('userTableBody').innerHTML = html || '<tr><td colspan="8" style="text-align: center; padding: 40px;">No users found.</td></tr>';
                    document.getElementById('selectAll').checked = false;
                });
        };

        document.getElementById('searchInput').addEventListener('keyup', fetchUsers);

        const approveUser = (id) => {
            fetch(`/admin/approve-user/${id}`, {
                method: "POST",
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => fetchUsers());
        };

        const deleteUser = (id) => {
            if (confirm("Delete this user?")) {
                fetch(`/admin/delete-user/${id}`, {
                    method: "POST",
                    headers: { 
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => fetchUsers());
            }
        };

        const bulkAction = (type) => {
            const ids = Array.from(document.querySelectorAll(".select-user:checked")).map(cb => cb.value);
            if (ids.length === 0) return alert("Select at least one user.");
            if (type === 'delete' && !confirm("Are you sure to delete selected users?")) return;
            
            fetch("{{ route('admin.bulk-action') }}", {
                method: "POST",
                headers: { 
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: `bulk_action=${type}&user_ids[]=${ids.join("&user_ids[]=")}`
            }).then(() => fetchUsers());
        };

        document.getElementById("selectAll").addEventListener("change", function () {
            document.querySelectorAll(".select-user").forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>
