@php
    // Determine the notifiable entity
    $notifiable = auth()->user();
    if (!$notifiable) {
        $adminId = session('admin_id');
        if ($adminId) {
            $notifiable = \App\Models\Admin::find($adminId);
        }
    }
    
    // Completely disable for Principal role
    if ($notifiable && method_exists($notifiable, 'getAttributes') && isset($notifiable->role) && $notifiable->role === 'principal') {
        $notifiable = null;
    }

    // If no character to notify, don't render anything
    if (!$notifiable) return;

    $unreadCount = $notifiable->unreadNotifications->count();
    $notifications = $notifiable->notifications()->take(10)->get();
@endphp

<div class="notification-container" style="position: relative; display: inline-block;">
    <button id="notiBell" class="noti-bell-btn">
        🔔@if($unreadCount > 0)<span class="noti-badge" id="notiBadge">{{ $unreadCount }}</span>@endif
    </button>

    <div id="notiDropdown" class="noti-dropdown">
        <div class="noti-header">Notifications</div>
        <div class="noti-body">
            @forelse($notifications as $n)
                <div class="noti-item {{ $n->read_at ? 'read' : 'unread' }}" 
                     onclick="handleNotificationClick(event, '{{ $n->id }}', '{{ $n->data['action_url'] ?? '#' }}', {{ $n->read_at ? 'true' : 'false' }})">
                    <span class="noti-icon">{{ $n->data['icon'] ?? '📢' }}</span>
                    <div class="noti-content">
                        <div class="noti-title">{{ $n->data['title'] }}</div>
                        <div class="noti-msg">{{ substr($n->data['message'], 0, 80) }}...</div>
                        <div class="noti-time">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="noti-empty">No notifications yet.</div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Brutalist Styles for Notifications */
    .noti-bell-btn {
        background: #fff;
        border: 2px solid #000;
        padding: 5px 10px;
        font-size: 1.5rem;
        cursor: pointer;
        position: relative;
        transition: 0.2s;
        box-shadow: 3px 3px 0px #000;
    }
    .noti-bell-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0px #000;
    }
    .noti-bell-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 1px 1px 0px #000;
    }

    .noti-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ff0000;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 2px 6px;
        border: 2px solid #000;
        border-radius: 4px;
        box-shadow: 1px 1px 0px #000;
    }

    .noti-dropdown {
        display: none;
        position: absolute;
        top: 50px;
        right: 0;
        width: 320px;
        background: #fff;
        border: 3px solid #000;
        box-shadow: 8px 8px 0px #000;
        z-index: 9999;
        max-height: 450px;
        overflow-y: auto;
    }
    .noti-dropdown.show {
        display: block;
        animation: brutalPop 0.15s cubic-bezier(0.18, 0.89, 0.32, 1.28);
    }
    @keyframes brutalPop {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .noti-header {
        padding: 12px;
        font-weight: 900;
        font-size: 1.1rem;
        border-bottom: 3px solid #000;
        background: #f0f0f0;
        text-transform: uppercase;
    }

    .noti-item {
        padding: 12px;
        border-bottom: 2px solid #000;
        display: flex;
        gap: 12px;
        cursor: pointer;
        transition: 0.2s;
    }
    .noti-item:hover {
        background: #fdfdfd;
        transform: scale(1.02);
    }
    .noti-item.unread {
        background: #fff9e6; /* Slight highlight for unread */
        font-weight: 600;
    }
    .noti-item.read {
        opacity: 0.85;
        background: #fff;
    }
    .noti-item:last-child {
        border-bottom: none;
    }

    .noti-icon {
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .noti-content {
        flex-grow: 1;
    }

    .noti-title {
        font-weight: 800;
        font-size: 0.95rem;
        margin-bottom: 2px;
        color: #000;
    }
    .noti-msg {
        font-size: 0.85rem;
        color: #333;
        line-height: 1.3;
    }
    .noti-time {
        font-size: 0.7rem;
        color: #666;
        margin-top: 5px;
        font-style: italic;
    }

    .noti-empty {
        padding: 30px 15px;
        text-align: center;
        color: #666;
        font-weight: 700;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('notiBell');
        const dropdown = document.getElementById('notiDropdown');

        if(bell) {
            bell.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });
        }

        document.addEventListener('click', function(e) {
            if (dropdown && !dropdown.contains(e.target) && !bell.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });

    async function handleNotificationClick(event, id, actionUrl, isRead) {
        if (isRead) {
            window.location.href = actionUrl;
            return;
        }

        // Prevent default and mark as read via AJAX
        event.preventDefault();
        
        try {
            const response = await fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await response.json();
            if (data.success) {
                // Update badge count
                const badge = document.getElementById('notiBadge');
                if (badge) {
                    let count = parseInt(badge.innerText) - 1;
                    if (count > 0) {
                        badge.innerText = count;
                    } else {
                        badge.remove();
                    }
                }
                // Redirect
                window.location.href = actionUrl;
            } else {
                window.location.href = actionUrl;
            }
        } catch (error) {
            console.error('Notification error:', error);
            window.location.href = actionUrl;
        }
    }
</script>
