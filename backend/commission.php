<?php
require_once __DIR__ . '/config.php';
apiHeaders();
$db = getDBConnection();
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$action = $_GET['action'] ?? $input['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'submit_transfer') {
        requireAuth();
        $username = sanitize($input['username'] ?? '');
        $amount = floatval($input['amount'] ?? 0);
        $bankName = sanitize($input['bankName'] ?? '');
        $transferDate = sanitize($input['transferDate'] ?? '');
        $adNumber = sanitize($input['adNumber'] ?? '');
        
        if (empty($username) || empty($amount) || empty($bankName) || empty($transferDate) || empty($adNumber)) {
            jsonError('الرجاء تعبئة جميع الحقول المطلوبة');
        }
        
        try {
            $stmt = $db->prepare("INSERT INTO commission_transfers (username, amount, bankName, transferDate, adNumber, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$username, $amount, $bankName, $transferDate, $adNumber]);
            jsonSuccess([], 'تم إرسال نموذج الحوالة بنجاح، سيتم المراجعة من قبل الإدارة.');
        } catch (Exception $e) {
            jsonError('حدث خطأ أثناء إرسال النموذج: ' . $e->getMessage());
        }
    }
}
jsonError('مسار غير صالح');
