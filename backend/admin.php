<?php
require_once __DIR__ . '/config.php';
apiHeaders();

$db = getDBConnection();
requireAdmin($db); // Only admins can access these endpoints

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = $input['action'] ?? $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'stats') {
        $stats = [
            'users' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'ads' => $db->query("SELECT COUNT(*) FROM ads")->fetchColumn(),
            'reports' => $db->query("SELECT COUNT(*) FROM reports WHERE status = 'pending'")->fetchColumn(),
            'commissions' => $db->query("SELECT SUM(amount) FROM commission_transfers WHERE status = 'approved'")->fetchColumn() ?: 0
        ];
        jsonSuccess($stats);
    } elseif ($action === 'users') {
        $users = $db->query("SELECT id, name, phone, role, isBanned, joinedDate FROM users ORDER BY id DESC")->fetchAll();
        jsonSuccess($users);
    } elseif ($action === 'reports') {
        $reports = $db->query("
            SELECT r.id, r.adId, r.adTitle, r.reason, r.reporterName, r.status, r.createdAt,
                   a.title as adCurrentTitle
            FROM reports r 
            LEFT JOIN ads a ON r.adId = a.id 
            ORDER BY r.createdAt DESC
        ")->fetchAll();
        jsonSuccess($reports);
    } elseif ($action === 'commissions') {
        $commissions = $db->query("SELECT * FROM commission_transfers ORDER BY createdAt DESC")->fetchAll();
        jsonSuccess($commissions);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'ban_user') {
        $userId = intval($input['user_id'] ?? 0);
        $db->prepare("UPDATE users SET isBanned = 1 WHERE id = ?")->execute([$userId]);
        jsonSuccess([], 'تم حظر المستخدم بنجاح');
    } elseif ($action === 'unban_user') {
        $userId = intval($input['user_id'] ?? 0);
        $db->prepare("UPDATE users SET isBanned = 0 WHERE id = ?")->execute([$userId]);
        jsonSuccess([], 'تم فك الحظر عن المستخدم');
    } elseif ($action === 'resolve_report') {
        $reportId = intval($input['report_id'] ?? 0);
        $db->prepare("UPDATE reports SET status = 'resolved' WHERE id = ?")->execute([$reportId]);
        jsonSuccess([], 'تم حل البلاغ');
    } elseif ($action === 'delete_ad') {
        $adId = intval($input['ad_id'] ?? 0);
        $db->prepare("DELETE FROM ads WHERE id = ?")->execute([$adId]);
        jsonSuccess([], 'تم حذف الإعلان بنجاح');
    } elseif ($action === 'approve_commission') {
        $id = intval($input['id'] ?? 0);
        $db->prepare("UPDATE commission_transfers SET status = 'approved' WHERE id = ?")->execute([$id]);
        jsonSuccess([], 'تم اعتماد الحوالة بنجاح');
    } elseif ($action === 'reject_commission') {
        $id = intval($input['id'] ?? 0);
        $db->prepare("UPDATE commission_transfers SET status = 'rejected' WHERE id = ?")->execute([$id]);
        jsonSuccess([], 'تم رفض الحوالة');
    }
}

jsonError('إجراء غير معروف في لوحة الإدارة');
