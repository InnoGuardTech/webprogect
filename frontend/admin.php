<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة - حراج الفاخر</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --admin-primary: var(--primary);
            --admin-secondary: var(--accent);
            --admin-bg: var(--bg-color);
            --admin-card: var(--card-bg);
            --admin-text: var(--text-main);
            --admin-muted: var(--text-muted);
        }
        body {
            background-color: var(--admin-bg);
            margin: 0;
            font-family: 'Cairo', sans-serif;
            color: var(--admin-text);
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--admin-primary) 0%, #1e293b 100%);
            color: white;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--admin-secondary);
            text-align: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }
        .nav-item {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            color: #cbd5e1;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.05rem;
        }
        .nav-item:hover, .nav-item.active {
            background: rgba(228, 168, 53, 0.15);
            color: var(--admin-secondary);
            transform: translateX(-5px);
        }
        
        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2.5rem 3rem;
            overflow-y: auto;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            background: var(--admin-card);
            padding: 1.25rem 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
        .header-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin: 0;
            color: var(--admin-primary);
        }
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-actions a {
            background: linear-gradient(135deg, var(--admin-secondary), #f59e0b);
            color: #fff;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(228, 168, 53, 0.3);
            transition: all 0.3s;
        }
        .header-actions a:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(228, 168, 53, 0.4);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }
        .stat-card {
            background: var(--admin-card);
            padding: 1.75rem;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0; width: 4px; height: 100%;
            background: var(--admin-secondary);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
        }
        .stat-card .value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--admin-primary);
            margin-bottom: 0.5rem;
        }
        .stat-card .label {
            color: var(--admin-muted);
            font-weight: 700;
            font-size: 0.95rem;
        }

        /* Tables */
        .table-container {
            background: var(--admin-card);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }
        .table-container h2 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: var(--admin-primary);
            font-size: 1.3rem;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 1rem;
        }
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .data-table th, .data-table td {
            padding: 1.2rem 1rem;
            text-align: right;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table th {
            background: #f8fafc;
            font-weight: 800;
            color: var(--admin-muted);
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        .data-table th:first-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        .data-table th:last-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        .data-table tr { transition: all 0.2s; }
        .data-table tbody tr:hover { background: #f8fafc; }

        /* Badges & Buttons */
        .badge {
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 800;
            display: inline-block;
        }
        .badge.banned { background: #fef2f2; color: #ef4444; }
        .badge.active { background: #ecfdf5; color: #10b981; }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            font-weight: 800;
            font-size: 0.8rem;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .action-btn:hover { transform: translateY(-2px); }
        .action-btn.danger { background: #fee2e2; color: #ef4444; }
        .action-btn.danger:hover { background: #fca5a5; color: white; }
        .action-btn.success { background: #d1fae5; color: #10b981; }
        .action-btn.success:hover { background: #6ee7b7; color: white; }

        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        <aside class="sidebar">
            <a href="index.php" class="sidebar-brand">
                <span>👑</span> لوحة الإدارة
            </a>
            <nav class="sidebar-nav">
                <div class="nav-item active" onclick="switchView('dashboard', this)"><span>📊</span> لوحة القيادة</div>
                <div class="nav-item" onclick="switchView('users', this)"><span>👥</span> إدارة المستخدمين</div>
                <div class="nav-item" onclick="switchView('reports', this)"><span>🚩</span> البلاغات</div>
                <div class="nav-item" onclick="switchView('commissions', this)"><span>💰</span> الحوالات والعمولات</div>
            </nav>
        </aside>

        <main class="content-area">
            <div class="header-top">
                <h1 class="header-title" id="page-title">نظرة عامة</h1>
                <div class="header-actions">
                    <button onclick="toggleTheme()" style="background:none; border:none; cursor:pointer; font-size:1.5rem; padding: 0;">🌓</button>
                    <a href="index.php">العودة للموقع 🏠</a>
                </div>
            </div>
            
            <div id="main-content" class="animate-fade-in">
                <!-- Views dynamically loaded here -->
            </div>
        </main>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        async function loadDashboard() {
            document.getElementById('page-title').innerText = 'لوحة القيادة';
            const content = document.getElementById('main-content');
            content.innerHTML = '<div style="text-align:center; padding:3rem; color:#94a3b8; font-weight:bold;">جاري التحميل... ⏳</div>';
            
            try {
                const res = await apiRequest('admin&action=stats');
                const s = res.data;
                content.innerHTML = `
                    <div class="stats-grid animate-fade-in">
                        <div class="stat-card">
                            <div class="value">${s.users}</div>
                            <div class="label">إجمالي الأعضاء 👥</div>
                        </div>
                        <div class="stat-card">
                            <div class="value">${s.ads}</div>
                            <div class="label">إجمالي الإعلانات 🏷️</div>
                        </div>
                        <div class="stat-card">
                            <div class="value">${s.reports}</div>
                            <div class="label">بلاغات قيد المراجعة 🚩</div>
                        </div>
                        <div class="stat-card">
                            <div class="value">${s.commissions}</div>
                            <div class="label">عمولات محصلة (ريال يمني) 💰</div>
                        </div>
                    </div>
                `;
            } catch(e) {}
        }

        async function loadUsers() {
            document.getElementById('page-title').innerText = 'إدارة المستخدمين';
            const content = document.getElementById('main-content');
            content.innerHTML = '<div style="text-align:center; padding:3rem; color:#94a3b8; font-weight:bold;">جاري التحميل... ⏳</div>';
            
            try {
                const res = await apiRequest('admin&action=users');
                let html = `
                    <div class="table-container animate-fade-in">
                        <h2>قائمة الأعضاء المسجلين</h2>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>م</th>
                                    <th>الاسم</th>
                                    <th>الجوال</th>
                                    <th>الدور</th>
                                    <th>الحالة</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                res.data.forEach(u => {
                    const statusBadge = u.isBanned ? '<span class="badge banned">محظور ❌</span>' : '<span class="badge active">نشط ✅</span>';
                    const actionBtn = u.isBanned 
                        ? `<button class="action-btn success" onclick="toggleBan(${u.id}, 'unban')">فك الحظر</button>`
                        : `<button class="action-btn danger" onclick="toggleBan(${u.id}, 'ban')">حظر</button>`;
                        
                    html += `
                        <tr>
                            <td>#${u.id}</td>
                            <td><strong style="color:var(--admin-primary)">${u.name}</strong></td>
                            <td dir="ltr" style="text-align:right">${u.phone}</td>
                            <td><span style="background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.8rem; font-weight:bold">${u.role}</span></td>
                            <td>${statusBadge}</td>
                            <td>${u.role !== 'admin' ? actionBtn : '<span style="color:#94a3b8">-</span>'}</td>
                        </tr>
                    `;
                });
                
                html += `</tbody></table></div>`;
                content.innerHTML = html;
            } catch(e) {}
        }

        async function loadReports() {
            document.getElementById('page-title').innerText = 'البلاغات';
            const content = document.getElementById('main-content');
            content.innerHTML = '<div style="text-align:center; padding:3rem; color:#94a3b8; font-weight:bold;">جاري التحميل... ⏳</div>';
            
            try {
                const res = await apiRequest('admin&action=reports');
                let html = `
                    <div class="table-container animate-fade-in">
                        <h2>البلاغات المرفوعة على الإعلانات</h2>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>رقم الإعلان</th>
                                    <th>المُبلّغ</th>
                                    <th>السبب</th>
                                    <th>الحالة</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                if (res.data.length === 0) {
                    html += `<tr><td colspan="5" style="text-align:center; padding:2rem; font-weight:bold; color:#64748b;">لا توجد بلاغات حالياً 🎉</td></tr>`;
                } else {
                    res.data.forEach(r => {
                        const resolveBtn = r.status === 'pending' 
                            ? `<button class="action-btn success" onclick="resolveReport(${r.id})">✔ محلول</button>`
                            : `-`;
                        const delAdBtn = `<button class="action-btn danger" onclick="deleteAd(${r.adId})">🗑️ حذف الإعلان</button>`;
                            
                        html += `
                            <tr>
                                <td><a href="ad.php?id=${r.adId}" target="_blank" style="color:var(--admin-secondary); font-weight:bold; text-decoration:none;">#${r.adId}</a></td>
                                <td>${r.reporterName}</td>
                                <td>${r.reason}</td>
                                <td>${r.status === 'pending' ? '<span class="badge" style="background:#fef3c7; color:#d97706">قيد الانتظار</span>' : '<span class="badge active">محلول</span>'}</td>
                                <td style="display:flex; gap:8px;">${resolveBtn} ${delAdBtn}</td>
                            </tr>
                        `;
                    });
                }
                
                html += `</tbody></table></div>`;
                content.innerHTML = html;
            } catch(e) {}
        }

        async function loadCommissions() {
            document.getElementById('page-title').innerText = 'الحوالات والعمولات';
            const content = document.getElementById('main-content');
            content.innerHTML = '<div style="text-align:center; padding:3rem; color:#94a3b8; font-weight:bold;">جاري التحميل... ⏳</div>';
            
            try {
                const res = await apiRequest('admin&action=commissions');
                let html = `
                    <div class="table-container animate-fade-in">
                        <h2>نماذج الحوالات المرفوعة</h2>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المُحوِّل</th>
                                    <th>البنك</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                    <th>رقم الإعلان</th>
                                    <th>الحالة</th>
                                    <th>إجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                if (res.data.length === 0) {
                    html += `<tr><td colspan="8" style="text-align:center; padding:2rem; font-weight:bold; color:#64748b;">لا توجد حوالات معلقة</td></tr>`;
                } else {
                    res.data.forEach(c => {
                        let actionBtn = '-';
                        let statusBadge = `<span class="badge" style="background:#f1f5f9;color:#475569;">معلقة ⏳</span>`;
                        if (c.status === 'pending') {
                            actionBtn = `
                                <div style="display:flex; gap:6px;">
                                    <button class="action-btn success" onclick="processCommission(${c.id}, 'approve')">✔ قبول</button>
                                    <button class="action-btn danger" onclick="processCommission(${c.id}, 'reject')">✖ رفض</button>
                                </div>
                            `;
                        } else if (c.status === 'approved') {
                            statusBadge = `<span class="badge active">مقبولة ✅</span>`;
                        } else if (c.status === 'rejected') {
                            statusBadge = `<span class="badge banned">مرفوضة ❌</span>`;
                        }
                            
                        html += `
                            <tr>
                                <td>${c.id}</td>
                                <td><strong>${c.username}</strong></td>
                                <td>${c.bankName}</td>
                                <td style="color:var(--admin-secondary); font-weight:900;">${c.amount} ر.ي</td>
                                <td>${c.transferDate}</td>
                                <td><a href="ad.php?id=${c.adNumber}" target="_blank" style="color:var(--admin-secondary); font-weight:bold; text-decoration:none;">#${c.adNumber}</a></td>
                                <td>${statusBadge}</td>
                                <td>${actionBtn}</td>
                            </tr>
                        `;
                    });
                }
                
                html += `</tbody></table></div>`;
                content.innerHTML = html;
            } catch(e) {}
        }

        async function toggleBan(userId, type) {
            if(!confirm('تأكيد الإجراء؟')) return;
            const action = type === 'ban' ? 'ban_user' : 'unban_user';
            try {
                await apiRequest('admin', 'POST', { action, user_id: userId });
                loadUsers(); // refresh
            } catch(e) {}
        }

        async function resolveReport(reportId) {
            try {
                await apiRequest('admin', 'POST', { action: 'resolve_report', report_id: reportId });
                loadReports();
            } catch(e) {}
        }

        async function deleteAd(adId) {
            if(!confirm('هل أنت متأكد من حذف هذا الإعلان نهائياً؟')) return;
            try {
                await apiRequest('admin', 'POST', { action: 'delete_ad', ad_id: adId });
                loadReports();
            } catch(e) {}
        }

        async function processCommission(id, type) {
            if(!confirm('تأكيد الإجراء؟')) return;
            const action = type === 'approve' ? 'approve_commission' : 'reject_commission';
            try {
                await apiRequest('admin', 'POST', { action, id: id });
                loadCommissions();
            } catch(e) {}
        }

        function switchView(view, el) {
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            
            if (view === 'dashboard') loadDashboard();
            if (view === 'users') loadUsers();
            if (view === 'reports') loadReports();
            if (view === 'commissions') loadCommissions();
        }

        // Init
        document.addEventListener('DOMContentLoaded', loadDashboard);
    </script>
</body>
</html>
