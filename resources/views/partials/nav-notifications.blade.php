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
    /* Academic Precision Styles for Notifications */
    .noti-bell-btn {
        background: #ffffff;
        border: 1px solid rgba(6, 20, 27, 0.08);
        padding: 8px 12px;
        font-size: 1.3rem;
        cursor: pointer;
        position: relative;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 12px;
        color: #06141B;
    }
    
    .noti-bell-btn:hover {
        background: #F2F4F3;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(6, 20, 27, 0.05);
    }

    .noti-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #253745;
        color: #CCD0CF;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 6px;
        border: 1px solid rgba(204, 208, 207, 0.2);
        border-radius: 20px;
        box-shadow: 0 2px 5px rgba(37, 55, 69, 0.2);
    }

    .noti-dropdown {
        display: none;
        position: absolute;
        top: 60px;
        right: 0;
        width: 350px;
        background: #ffffff;
        border: 1px solid rgba(6, 20, 27, 0.08);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(6, 20, 27, 0.12);
        z-index: 9999;
        max-height: 500px;
        overflow: hidden;
    }

    .noti-dropdown.show {
        display: block;
        animation: smoothSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes smoothSlide {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .noti-header {
        padding: 20px 25px;
        font-weight: 800;
        font-size: 1.1rem;
        border-bottom: 1px solid rgba(6, 20, 27, 0.05);
        background: #ffffff;
        color: #06141B;
        letter-spacing: -0.5px;
    }

    .noti-body {
        overflow-y: auto;
        max-height: 400px;
    }

    .noti-item {
        padding: 18px 25px;
        border-bottom: 1px solid rgba(6, 20, 27, 0.03);
        display: flex;
        gap: 15px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .noti-item:hover {
        background: #F8F9F9;
    }

    .noti-item.unread {
        background: rgba(37, 55, 69, 0.02);
    }

    .noti-item.read {
        opacity: 0.7;
    }

    .noti-item:last-child {
        border-bottom: none;
    }

    .noti-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
        background: #F2F4F3;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .noti-content {
        flex-grow: 1;
    }

    .noti-title {
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 3px;
        color: #06141B;
    }

    .noti-msg {
        font-size: 0.85rem;
        color: #4A5568;
        line-height: 1.4;
    }

    .noti-time {
        font-size: 0.75rem;
        color: #A0AEC0;
        margin-top: 6px;
        font-weight: 500;
    }

    .noti-empty {
        padding: 50px 25px;
        text-align: center;
        color: #A0AEC0;
        font-weight: 600;
        font-size: 0.9rem;
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
