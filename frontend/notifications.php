<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php');
    exit;
}
$pageTitle = 'الإشعارات - حراج اليمن';
require_once 'includes/header.php';
?>
    <style>
        .notifications-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 1rem;
        }
        .notification-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
        }
        .notification-card.unread {
            background: var(--primary-light);
            border-right: 4px solid var(--primary);
        }
        .icon-circle {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .noti-content { flex: 1; }
        .noti-title {
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
        }
        .noti-body {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin: 0 0 0.5rem 0;
            line-height: 1.6;
        }
        .noti-date {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 700;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            border: 1px dashed var(--border-color);
            color: var(--text-muted);
        }
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>

    <main class="notifications-container animate-fade-in">
        <div class="page-header">
            <h1 style="margin:0; font-size:1.8rem; color:var(--primary);">التنبيهات والإشعارات 🔔</h1>
        </div>

        <div id="notifications-list">
            <div style="text-align:center; padding:3rem; font-weight:bold; color:var(--text-muted);">جاري التحميل...</div>
        </div>
    </main>

    <script src="assets/js/app.js"></script>
    <script>
        async function loadNotifications() {
            try {
                const res = await apiRequest('user&action=notifications');
                const list = document.getElementById('notifications-list');
                
                if (res.data.length === 0) {
                    list.innerHTML = `
                        <div class="empty-state animate-fade-in">
                            <div class="empty-state-icon">🔕</div>
                            <h3 style="margin:0;">لا توجد إشعارات جديدة</h3>
                            <p>أنت على اطلاع دائم بجميع المستجدات.</p>
                        </div>
                    `;
                } else {
                    let html = '';
                    res.data.forEach(n => {
                        html += `
                            <div class="notification-card ${n.isRead ? '' : 'unread'}">
                                <div class="icon-circle">🔔</div>
                                <div class="noti-content">
                                    <h4 class="noti-title">${n.title}</h4>
                                    <p class="noti-body">${n.content}</p>
                                    <span class="noti-date">${n.date}</span>
                                </div>
                            </div>
                        `;
                    });
                    list.innerHTML = html;
                    
                    // Mark as read silently
                    apiRequest('user', 'POST', { action: 'read_notifications' }).catch(e=>e);
                }
            } catch (err) {
                document.getElementById('notifications-list').innerHTML = '<div style="color:red; text-align:center;">حدث خطأ أثناء تحميل التنبيهات.</div>';
            }
        }

        document.addEventListener('DOMContentLoaded', loadNotifications);
    </script>
<?php require_once 'includes/footer.php'; ?>
