<?php
require_once __DIR__ . '/../config.php';
$db = getDBConnection();

echo "Starting to insert rich sample data...\n";

// 1. Users
$users = [
    ['name' => 'معرض سيارات الأحلام', 'phone' => '771112223', 'password' => '123456', 'role' => 'user'],
    ['name' => 'مؤسسة إعمار للتطوير العقاري', 'phone' => '734445556', 'password' => '123456', 'role' => 'user'],
    ['name' => 'محمد عبدالله للتقنية', 'phone' => '711222333', 'password' => '123456', 'role' => 'user'],
    ['name' => 'مركز النخبة التجاري', 'phone' => '779998887', 'password' => '123456', 'role' => 'user']
];

foreach ($users as $u) {
    try {
        $hashed = password_hash($u['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT IGNORE INTO users (name, phone, password, role, joinedDate, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$u['name'], $u['phone'], $hashed, $u['role'], date('Y-m'), 5.0]);
    } catch(Exception $e) {}
}

// Get user IDs
$stmt = $db->query("SELECT id, phone FROM users");
$userMap = [];
while($row = $stmt->fetch()) {
    $userMap[$row['phone']] = $row['id'];
}

$ad_user_1 = $userMap['771112223'] ?? 1;
$ad_user_2 = $userMap['734445556'] ?? 1;
$ad_user_3 = $userMap['711222333'] ?? 1;
$ad_user_4 = $userMap['779998887'] ?? 1;

// 2. Rich Ads
$ads = [
    [
        'user_id' => $ad_user_1, 'category_id' => 1, 'title' => 'هونداي توسان 2022 فل كامل', 'price' => 22000000, 'city' => 'صنعاء',
        'description' => "سيارة هونداي توسان موديل 2022، بانوراما، بصمة، شاشة، كاميرا خلفية. ممشى 15 ألف كيلو فقط. استخدام شخصي ونظيفة جداً.",
        'image' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=800',
        'status' => 'active', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 days'))
    ],
    [
        'user_id' => $ad_user_2, 'category_id' => 2, 'title' => 'عمارة تجارية استثمارية شارع الستين', 'price' => 350000000, 'city' => 'صنعاء',
        'description' => "عمارة استثمارية على شارع الستين الرئيسي، تتكون من 6 طوابق، كل طابق فيه 3 شقق، و 4 فتحات تجارية مأجرة بالكامل.",
        'image' => 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=800',
        'status' => 'active', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'user_id' => $ad_user_3, 'category_id' => 3, 'title' => 'ماك بوك برو M2 جديد بكرتونه', 'price' => 1200000, 'city' => 'عدن',
        'description' => "لابتوب ابل ماك بوك برو بشريحة M2، رامات 16 جيجا، هاردسك 512 جيجا SSD، الجهاز جديد لم يفتح بضمان الوكيل.",
        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800',
        'status' => 'active', 'created_at' => date('Y-m-d H:i:s', strtotime('-5 hours'))
    ],
    [
        'user_id' => $ad_user_4, 'category_id' => 4, 'title' => 'غرفة نوم ملكية خشب دمياطي', 'price' => 850000, 'city' => 'تعز',
        'description' => "غرفة نوم تتكون من سرير مزدوج، دولاب 6 ضلف، تسريحة مع كرسي، و2 كمدينات. خشب دمياطي فاخر وتنجيد راقي.",
        'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800',
        'status' => 'active', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
    ],
    [
        'user_id' => $ad_user_1, 'category_id' => 1, 'title' => 'تويوتا ميكروباص (باص هايس) 2018', 'price' => 18000000, 'city' => 'إب',
        'description' => "باص هايس 15 راكب، مكينة وبودي وكالة، مكيف مركزي يعمل بكفاءة، جاهز للشغل.",
        'image' => 'https://images.unsplash.com/photo-1582046869408-9df2492f1f31?auto=format&fit=crop&w=800',
        'status' => 'active', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 hours'))
    ],
    [
        'user_id' => $ad_user_3, 'category_id' => 3, 'title' => 'شاشة سامسونج سمارت 65 بوصة 4K', 'price' => 350000, 'city' => 'المكلا',
        'description' => "شاشة ذكية سامسونج 65 بوصة، دقة 4K UHD، تدعم اليوتيوب ونتفليكس، مستخدمة شهرين فقط وأخو الجديد.",
        'image' => 'https://images.unsplash.com/photo-1593305841991-05c297ba4575?auto=format&fit=crop&w=800',
        'status' => 'active', 'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))
    ]
];

foreach ($ads as $ad) {
    try {
        $stmt = $db->prepare("INSERT INTO ads (user_id, category_id, title, price, city, description, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ad['user_id'], $ad['category_id'], $ad['title'], $ad['price'], $ad['city'], $ad['description'], $ad['image'], $ad['status'], $ad['created_at']]);
    } catch(Exception $e) {}
}

echo "Rich ads inserted.\n";

// 3. Comments/Messages
$stmt = $db->query("SELECT id FROM ads ORDER BY id DESC LIMIT 5");
$adIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($adIds as $adId) {
    try {
        // Add fake comment
        $stmt = $db->prepare("INSERT INTO comments (ad_id, user_id, content, created_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$adId, $ad_user_4, 'ما شاء الله، هل السعر قابل للتفاوض؟', date('Y-m-d H:i:s')]);
    } catch(Exception $e) {}
}

echo "Sample data generation complete!\n";
