<?php
require_once __DIR__ . '/config.php';
apiHeaders();

$db = getDBConnection();
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = $_GET['action'] ?? $input['action'] ?? '';

if ($method === 'GET') {
    if ($action === 'profile') {
        $userId = intval($_GET['id'] ?? 0);
        
        $stmt = $db->prepare("SELECT id, name, joinedDate, rating FROM users WHERE id = ? AND isBanned = 0");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        if (!$user) jsonError('المستخدم غير موجود', 404);
        
        // Fetch User's Ads
        $adsStmt = $db->prepare("SELECT id, title, price, city, createdAt, images FROM ads WHERE userId = ? ORDER BY createdAt DESC");
        $adsStmt->execute([$userId]);
        $ads = array_map(function($a) {
            $imgs = json_decode($a['images'], true);
            return [
                'id' => $a['id'],
                'title' => $a['title'],
                'price' => formatPrice($a['price']),
                'city' => $a['city'],
                'date' => formatArabicDate($a['createdAt']),
                'image' => (!empty($imgs)) ? $imgs[0] : 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&q=80'
            ];
        }, $adsStmt->fetchAll());
        
        // Fetch User's Reviews
        $revStmt = $db->prepare("SELECT authorName, rating, content, createdAt FROM reviews WHERE targetUserId = ? ORDER BY createdAt DESC");
        $revStmt->execute([$userId]);
        $reviews = array_map(function($r) {
            return [
                'author' => $r['authorName'],
                'rating' => $r['rating'],
                'content' => $r['content'],
                'date' => formatArabicDate($r['createdAt'])
            ];
        }, $revStmt->fetchAll());
        
        jsonSuccess([
            'user' => $user,
            'ads' => $ads,
            'reviews' => $reviews
        ]);
    } elseif ($action === 'notifications') {
        requireAuth();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE userId = ? ORDER BY createdAt DESC LIMIT 50");
        $stmt->execute([$_SESSION['user_id']]);
        $notifications = array_map(function($n) {
            return [
                'id' => $n['id'],
                'title' => $n['title'],
                'content' => $n['content'],
                'isRead' => (bool)$n['isRead'],
                'date' => formatArabicDate($n['createdAt'])
            ];
        }, $stmt->fetchAll());
        jsonSuccess($notifications);
    }
} elseif ($method === 'POST') {
    requireAuth();
    if ($action === 'add_review') {
        $targetUserId = intval($input['target_id'] ?? 0);
        $rating = intval($input['rating'] ?? 5);
        $content = sanitize($input['content'] ?? '');
        
        if ($targetUserId == $_SESSION['user_id']) jsonError('لا يمكنك تقييم نفسك');
        if (empty($content)) jsonError('يرجى كتابة نص التقييم');
        
        $db->prepare("INSERT INTO reviews (targetUserId, authorName, rating, content) VALUES (?, ?, ?, ?)")
           ->execute([$targetUserId, $_SESSION['user_name'], $rating, $content]);
           
        // Update user rating average
        $db->prepare("UPDATE users SET rating = (SELECT AVG(rating) FROM reviews WHERE targetUserId = ?) WHERE id = ?")
           ->execute([$targetUserId, $targetUserId]);
           
        jsonSuccess([], 'تم إضافة التقييم بنجاح');
    } elseif ($action === 'read_notifications') {
        $db->prepare("UPDATE notifications SET isRead = 1 WHERE userId = ?")->execute([$_SESSION['user_id']]);
        jsonSuccess();
    } elseif ($action === 'update_settings') {
        $name = sanitize($input['name'] ?? '');
        $phone = sanitize($input['phone'] ?? '');
        $password = $input['password'] ?? '';
        
        if (empty($name) || empty($phone)) jsonError('يرجى تعبئة الاسم ورقم الجوال');
        
        if (!empty($password)) {
            if (strlen($password) < 6) jsonError('كلمة المرور يجب أن تكون 6 أحرف على الأقل');
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $db->prepare("UPDATE users SET name = ?, phone = ?, password = ? WHERE id = ?")->execute([$name, $phone, $hashed, $_SESSION['user_id']]);
        } else {
            $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?")->execute([$name, $phone, $_SESSION['user_id']]);
        }
        
        $_SESSION['user_name'] = $name;
        jsonSuccess([], 'تم حفظ الإعدادات بنجاح');
    }
}

jsonError('طلب غير صالح');
