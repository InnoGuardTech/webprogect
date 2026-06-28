<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// Default page title
$pageTitle = $pageTitle ?? 'حراج اليمن - منصة البيع والشراء الأولى';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Unified Glass Header -->
    <header class="glass-header">
        <div class="header-container">
            <a href="index.php" class="header-logo">
                <span class="header-logo-badge">حراج</span>
                <span>اليمن</span>
            </a>
            
            <div class="header-search">
                <input type="text" id="global-search-input" placeholder="ابحث عن سيارة، عقار، أو سلعة..." onkeydown="if(event.key === 'Enter') handleGlobalSearch()">
                <button onclick="handleGlobalSearch()">🔍</button>
            </div>
            
            <div class="header-actions" id="user-menu-area">
                <button onclick="toggleTheme()" class="icon-btn" title="الوضع الداكن">🌓</button>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="post.php" class="btn-gold" style="white-space:nowrap;">+ أضف إعلانك</a>
                    
                    <a href="favorites.php" class="icon-link" title="المفضلة">❤️</a>
                    
                    <a href="notifications.php" class="icon-link" title="الإشعارات" style="position:relative;">
                        🔔
                        <span id="notif-badge" style="display:none; position:absolute; top:-6px; right:-6px; background:var(--secondary); color:#1a1a2e; border-radius:50%; font-size:0.65rem; width:18px; height:18px; align-items:center; justify-content:center; font-weight:900; box-shadow:0 2px 5px rgba(0,0,0,0.2);"></span>
                    </a>
                    
                    <a href="messages.php" class="icon-link" title="الرسائل" style="position:relative;">
                        💬
                        <span id="chat-badge" style="display:none; position:absolute; top:-6px; right:-6px; background:var(--danger); color:white; border-radius:50%; font-size:0.65rem; width:18px; height:18px; align-items:center; justify-content:center; font-weight:900; box-shadow:0 2px 5px rgba(0,0,0,0.2);"></span>
                    </a>
                    
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin.php" class="admin-badge-link" title="لوحة الإدارة">👑 الإدارة</a>
                    <?php endif; ?>
                    
                    <a href="user.php?id=<?php echo $_SESSION['user_id']; ?>" class="profile-link">👤 حسابي</a>
                    
                    <button onclick="logout()" class="logout-btn">خروج</button>
                <?php else: ?>
                    <a href="auth.php" class="btn-gold">دخول / تسجيل</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
