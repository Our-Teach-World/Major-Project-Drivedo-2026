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

        .btn-success:disabled {
            background-color: #a5d6a7;
            border-color: #a5d6a7;
            cursor: not-allowed;
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

        .btn-danger:disabled {
            background-color: #ef9a9a;
            border-color: #ef9a9a;
            cursor: not-allowed;
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

        tr.removing {
            opacity: 0;
            transition: opacity 0.4s ease;
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

        /* Toast Notification */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            padding: 14px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
            min-width: 220px;
        }

        .toast-success {
            background-color: #2e7d32;
            color: #ffffff;
            border-left: 5px solid #1b5e20;
        }

        .toast-error {
            background-color: #c62828;
            color: #ffffff;
            border-left: 5px solid #ad1457;
        }

        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }

        @keyframes slideOut {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(120%); opacity: 0; }
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

    <!-- Toast Container -->
    <div id="toast-container"></div>

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
                    <tr id="user-row-{{ $user->id }}">
                        <td><input type="checkbox" class="select-user" value="{{ $user->id }}" style="cursor: pointer;"></td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>••••••••</td>
                        <td>{{ $user->created_at->format('d-m-Y H:i A') }}</td>
                        <td id="status-{{ $user->id }}">
                            @if ($user->status === 'pending')
                                <span class="status-pending">Pending</span>
                            @elseif ($user->status === 'approved')
                                <span class="status-approved">✔ Approved</span>
                            @else
                                <span class="status-pending">{{ ucfirst($user->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell" id="actions-{{ $user->id }}">
                                @if ($user->status === 'pending')
                                    <button id="approve-btn-{{ $user->id }}" onclick="approveUser({{ $user->id }})" class="btn btn-success">Approve</button>
                                @else
                                    <span style="color: #2e7d32; font-weight: 600;">✔ Approved</span>
                                @endif
                                <button id="delete-btn-{{ $user->id }}" onclick="deleteUser({{ $user->id }})" class="btn btn-danger">Delete</button>
                                <a href="{{ route('admin.edit-user', $user->id) }}" class="btn" style="background-color: #3b82f6; color: #ffffff; border-color: #3b82f6;">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="empty-row">
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
        const CSRF = '{{ csrf_token() }}';
        const AJAX_HEADERS = {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': CSRF,
            'X-Requested-With': 'XMLHttpRequest'   // ← This is the key fix so request()->ajax() returns true
        };

        // ── Toast Notification ─────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // ── Search (re-renders full table via AJAX) ────────────────────────
        const fetchUsers = () => {
            const q = document.getElementById('searchInput').value;
            fetch(`{{ route('admin.users') }}?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(users => {
                if (users.length === 0) {
                    document.getElementById('userTableBody').innerHTML =
                        '<tr id="empty-row"><td colspan="8" style="text-align:center;padding:40px;">No users found.</td></tr>';
                    return;
                }
                const html = users.map(u => `
                    <tr id="user-row-${u.id}">
                        <td><input type="checkbox" class="select-user" value="${u.id}" style="cursor:pointer;"></td>
                        <td>${u.id}</td>
                        <td>${u.username}</td>
                        <td>${u.role.charAt(0).toUpperCase() + u.role.slice(1)}</td>
                        <td>••••••••</td>
                        <td>${new Date(u.created_at).toLocaleString('en-IN')}</td>
                        <td id="status-${u.id}">
                            <span class="${u.status === 'approved' ? 'status-approved' : 'status-pending'}">
                                ${u.status === 'approved' ? '✔ Approved' : 'Pending'}
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell" id="actions-${u.id}">
                                ${u.status === 'pending'
                                    ? `<button id="approve-btn-${u.id}" onclick="approveUser(${u.id})" class="btn btn-success">Approve</button>`
                                    : `<span style="color:#2e7d32;font-weight:600;">✔ Approved</span>`}
                                <button id="delete-btn-${u.id}" onclick="deleteUser(${u.id})" class="btn btn-danger">Delete</button>
                                <a href="${window.location.origin}/admin/edit-user/${u.id}" class="btn" style="background-color:#3b82f6;color:#fff;border-color:#3b82f6;">Edit</a>
                            </div>
                        </td>
                    </tr>
                `).join('');
                document.getElementById('userTableBody').innerHTML = html;
                document.getElementById('selectAll').checked = false;
            })
            .catch(() => showToast('Failed to load users.', 'error'));
        };

        document.getElementById('searchInput').addEventListener('keyup', fetchUsers);

        // ── Approve (instant DOM update — no full re-fetch) ────────────────
        const approveUser = (id) => {
            const btn = document.getElementById(`approve-btn-${id}`);
            if (btn) { btn.disabled = true; btn.textContent = '...'; }

            fetch(`/admin/approve-user/${id}`, {
                method: 'POST',
                headers: AJAX_HEADERS
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update status badge in-place
                    document.getElementById(`status-${id}`).innerHTML =
                        '<span class="status-approved">✔ Approved</span>';

                    // Replace Approve button with approved text
                    const actionsCell = document.getElementById(`actions-${id}`);
                    if (btn) {
                        btn.replaceWith((() => {
                            const s = document.createElement('span');
                            s.style.cssText = 'color:#2e7d32;font-weight:600;';
                            s.textContent = '✔ Approved';
                            return s;
                        })());
                    }

                    showToast('User approved successfully!');
                } else {
                    if (btn) { btn.disabled = false; btn.textContent = 'Approve'; }
                    showToast('Failed to approve user.', 'error');
                }
            })
            .catch(() => {
                if (btn) { btn.disabled = false; btn.textContent = 'Approve'; }
                showToast('Network error. Please try again.', 'error');
            });
        };

        // ── Delete (instant DOM row removal) ──────────────────────────────
        const deleteUser = (id) => {
            if (!confirm('Are you sure you want to delete this user?')) return;

            const delBtn = document.getElementById(`delete-btn-${id}`);
            if (delBtn) { delBtn.disabled = true; delBtn.textContent = '...'; }

            fetch(`/admin/delete-user/${id}`, {
                method: 'POST',
                headers: AJAX_HEADERS
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`user-row-${id}`);
                    if (row) {
                        row.classList.add('removing');
                        setTimeout(() => {
                            row.remove();
                            // Show empty message if table is now empty
                            if (document.getElementById('userTableBody').querySelectorAll('tr').length === 0) {
                                document.getElementById('userTableBody').innerHTML =
                                    '<tr id="empty-row"><td colspan="8" style="text-align:center;padding:40px;">No users found.</td></tr>';
                            }
                        }, 400);
                    }
                    showToast('User deleted successfully!');
                } else {
                    if (delBtn) { delBtn.disabled = false; delBtn.textContent = 'Delete'; }
                    showToast('Failed to delete user.', 'error');
                }
            })
            .catch(() => {
                if (delBtn) { delBtn.disabled = false; delBtn.textContent = 'Delete'; }
                showToast('Network error. Please try again.', 'error');
            });
        };

        // ── Bulk Action ────────────────────────────────────────────────────
        const bulkAction = (type) => {
            const ids = Array.from(document.querySelectorAll('.select-user:checked')).map(cb => cb.value);
            if (ids.length === 0) return showToast('Select at least one user.', 'error');
            if (type === 'delete' && !confirm(`Delete ${ids.length} selected user(s)?`)) return;

            fetch('{{ route('admin.bulk-action') }}', {
                method: 'POST',
                headers: AJAX_HEADERS,
                body: `bulk_action=${type}&user_ids[]=${ids.join('&user_ids[]=')}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (type === 'delete') {
                        ids.forEach(id => {
                            const row = document.getElementById(`user-row-${id}`);
                            if (row) {
                                row.classList.add('removing');
                                setTimeout(() => row.remove(), 400);
                            }
                        });
                        showToast(`${ids.length} user(s) deleted!`);
                        document.getElementById('selectAll').checked = false;
                        setTimeout(() => {
                            if (document.getElementById('userTableBody').querySelectorAll('tr:not(.removing)').length === 0) {
                                document.getElementById('userTableBody').innerHTML =
                                    '<tr id="empty-row"><td colspan="8" style="text-align:center;padding:40px;">No users found.</td></tr>';
                            }
                        }, 500);
                    } else if (type === 'approve') {
                        ids.forEach(id => {
                            document.getElementById(`status-${id}`).innerHTML =
                                '<span class="status-approved">✔ Approved</span>';
                            const btn = document.getElementById(`approve-btn-${id}`);
                            if (btn) {
                                const s = document.createElement('span');
                                s.style.cssText = 'color:#2e7d32;font-weight:600;';
                                s.textContent = '✔ Approved';
                                btn.replaceWith(s);
                            }
                        });
                        document.getElementById('selectAll').checked = false;
                        showToast(`${ids.length} user(s) approved!`);
                    }
                } else {
                    showToast('Bulk action failed.', 'error');
                }
            })
            .catch(() => showToast('Network error. Please try again.', 'error'));
        };

        // ── Select All Checkbox ────────────────────────────────────────────
        document.getElementById('selectAll').addEventListener('change', function () {
            document.querySelectorAll('.select-user').forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>
