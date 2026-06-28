<?php
require_once __DIR__ . '/../config.php';
$db = getDBConnection();

echo "=== FIXING & COMPLETING DATABASE ===\n\n";

// Get proper user IDs
$users = $db->query("SELECT id, name, phone, role FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
echo "Users in DB:\n";
foreach($users as $u){
    echo "  [{$u['id']}] {$u['name']} ({$u['phone']}) - {$u['role']}\n";
}

// Find admin user
$admin = null; $seller1 = null; $seller2 = null; $seller3 = null;
foreach($users as $u){
    if($u['role'] === 'admin' && !$admin) $admin = $u['id'];
    elseif(!$seller1) $seller1 = $u['id'];
    elseif(!$seller2) $seller2 = $u['id'];
    elseif(!$seller3) $seller3 = $u['id'];
}

echo "\nUsing: admin=$admin, sellers=$seller1, $seller2, $seller3\n\n";

// Delete ads with wrong user_id (if any)
// First check current ads
$ads = $db->query("SELECT id, title, userId FROM ads")->fetchAll(PDO::FETCH_ASSOC);
echo "Current ads:\n";
foreach($ads as $a){ echo "  [{$a['id']}] userId={$a['userId']} - {$a['title']}\n"; }

// Add more rich, real-looking ads
$newAds = [
    [
        'userId' => $seller1,
        'title' => 'تويوتا لاندكروزر برادو 2022 - قمة النظافة',
        'description' => "للبيع تويوتا برادو 4 سلندر، موديل 2022، اللون أبيض لؤلؤي مع داخلية جلد بيج فاخرة. الممشى 35,000 كيلومتر فقط. جميع الخدمات عند الوكيل. السيارة نظيفة من الداخل والخارج كالمرآة، لا توجد حوادث ولا ذحل.\n\nالمواصفات:\n- ناقل حركة أوتوماتيك\n- فتحة سقف\n- شاشة أندرويد\n- كاميرا خلفية 360 درجة\n- حساسات أمامية وخلفية",
        'category' => 'cars',
        'city' => 'صنعاء',
        'price' => 32000000,
        'images' => json_encode(['https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['الماركة' => 'تويوتا', 'الموديل' => 'برادو', 'سنة الصنع' => '2022', 'الممشى' => '35,000 كم', 'ناقل الحركة' => 'أوتوماتيك', 'اللون' => 'أبيض لؤلؤي']),
        'carBrand' => 'تويوتا', 'carYear' => '2022', 'carTransmission' => 'auto', 'carMileage' => 35000,
    ],
    [
        'userId' => $seller2,
        'title' => 'شقة راقية للإيجار في شارع القيادة - صنعاء',
        'description' => "شقة مفروشة بالكامل وبمستوى راقٍ جداً في شارع القيادة. الطابق الثالث بمصعد. تتكون من:\n- 3 غرف نوم (جميعها بحمام)\n- صالة رئيسية فسيحة\n- مطبخ مجهز بالكامل\n- غرفة غسيل\n- موقف خاص للسيارة\n\nالإيجار شامل الخدمات (ماء، كهرباء نظام، انترنت). مناسبة للعائلات.",
        'category' => 'realestate',
        'city' => 'صنعاء',
        'price' => 500000,
        'images' => json_encode(['https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['نوع العقار' => 'شقة', 'عدد الغرف' => '3 غرف', 'نوع العقد' => 'إيجار', 'الطابق' => 'الثالث', 'المساحة' => '180 متر مربع']),
        'carBrand' => null, 'carYear' => null, 'carTransmission' => null, 'carMileage' => null,
    ],
    [
        'userId' => $seller3,
        'title' => 'سامسونج جالاكسي S24 Ultra - جديد بالكرتون',
        'description' => "سامسونج S24 Ultra 256GB، اللون Black Titanium الأناقة والقوة في جهاز واحد. الجهاز جديد لم يفتح مع:\n✓ ضمان الوكيل سنة كاملة\n✓ القلم S-Pen مدمج\n✓ شاشة 6.8 بوصة Dynamic AMOLED 2X\n✓ رامات 12 جيجا\n✓ كاميرا 200 ميجابكسل",
        'category' => 'electronics',
        'city' => 'عدن',
        'price' => 750000,
        'images' => json_encode(['https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['الحالة' => 'جديد بكرتونه', 'الذاكرة' => '256 جيجا', 'الرامات' => '12 جيجا', 'الضمان' => '12 شهراً', 'اللون' => 'أسود تيتانيوم']),
        'carBrand' => null, 'carYear' => null, 'carTransmission' => null, 'carMileage' => null,
    ],
    [
        'userId' => $seller1,
        'title' => 'أرض سكنية ركنية في حي السبعين - صنعاء',
        'description' => "للبيع أرض سكنية ركنية ممتازة في حي السبعين الراقي. المساحة الإجمالية 400 متر مربع، واجهتين:\n- الواجهة الرئيسية: 20 متراً على شارع 12 متر معبد\n- الواجهة الجانبية: 20 متراً على شارع 8 متر\n\nالأرض مستوية تماماً وجاهزة للبناء. موقع استراتيجي بالقرب من جميع الخدمات.",
        'category' => 'realestate',
        'city' => 'صنعاء',
        'price' => 180000000,
        'images' => json_encode(['https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['نوع العقار' => 'أرض', 'المساحة' => '400 متر مربع', 'نوع العقد' => 'بيع', 'الواجهة' => 'ركنية', 'الوضع' => 'جاهزة للبناء']),
        'carBrand' => null, 'carYear' => null, 'carTransmission' => null, 'carMileage' => null,
    ],
    [
        'userId' => $seller2,
        'title' => 'لاب توب Dell XPS 15 - للمحترفين',
        'description' => "Dell XPS 15 بمعالج Intel Core i9 الجيل الثالث عشر:\n- شاشة 15.6 بوصة OLED 3.5K\n- رامات 32 جيجا DDR5\n- هاردسك 1 تيرا SSD NVMe\n- كارت شاشة NVIDIA RTX 4070\n- مستخدم 6 أشهر بحالة ممتازة مع الشنطة والشاحن الأصلي",
        'category' => 'electronics',
        'city' => 'تعز',
        'price' => 950000,
        'images' => json_encode(['https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['المعالج' => 'Intel Core i9', 'الرامات' => '32 جيجا', 'الهاردسك' => '1 تيرا SSD', 'الشاشة' => '15.6 بوصة OLED', 'الكارت' => 'RTX 4070']),
        'carBrand' => null, 'carYear' => null, 'carTransmission' => null, 'carMileage' => null,
    ],
    [
        'userId' => $seller3,
        'title' => 'بي ام دبليو X5 موديل 2021 - نظيفة جداً',
        'description' => "للبيع BMW X5 xDrive40i موديل 2021، اللون رمادي غرافيت مع داخلية جلد أسود. الممشى 45,000 كم. السيارة مصانة عند وكيل BMW. كروز كنترول تكيفي، حارة مسار تلقائية، نظام HUD للعرض على الزجاج.",
        'category' => 'cars',
        'city' => 'الحديدة',
        'price' => 55000000,
        'images' => json_encode(['https://images.unsplash.com/photo-1555215695-3004980ad54e?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['الماركة' => 'BMW', 'الموديل' => 'X5', 'سنة الصنع' => '2021', 'الممشى' => '45,000 كم', 'ناقل الحركة' => 'أوتوماتيك', 'اللون' => 'رمادي']),
        'carBrand' => 'BMW', 'carYear' => '2021', 'carTransmission' => 'auto', 'carMileage' => 45000,
    ],
    [
        'userId' => $seller1,
        'title' => 'مطعم جاهز للاستلام فوراً - موقع حيوي',
        'description' => "للبيع مطعم شعبي مفروش بالكامل وجاهز للعمل الفوري في موقع حيوي قرب سوق تجاري كبير. المطعم مؤجر بعقد 3 سنوات، مجهز بـ:\n- مطبخ صناعي كامل\n- 15 طاولة وكراسي\n- نظام كاشير رقمي\n- مولد كهربائي\n- تكييف مركزي",
        'category' => 'services',
        'city' => 'صنعاء',
        'price' => 12000000,
        'images' => json_encode(['https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80']),
        'specifications' => json_encode(['نوع النشاط' => 'مطعم', 'الحالة' => 'جاهز للعمل', 'عدد الطاولات' => '15 طاولة', 'عقد الإيجار' => '3 سنوات']),
        'carBrand' => null, 'carYear' => null, 'carTransmission' => null, 'carMileage' => null,
    ],
];

$stmt = $db->prepare("INSERT INTO ads (userId, title, description, category, city, price, images, specifications, carBrand, carYear, carTransmission, carMileage, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");
foreach($newAds as $ad){
    try {
        $stmt->execute([
            $ad['userId'], $ad['title'], $ad['description'],
            $ad['category'], $ad['city'], $ad['price'],
            $ad['images'], $ad['specifications'],
            $ad['carBrand'], $ad['carYear'], $ad['carTransmission'], $ad['carMileage']
        ]);
        echo "Added ad: {$ad['title']}\n";
    } catch(Exception $e){
        echo "Error adding ad: " . $e->getMessage() . "\n";
    }
}

// Add realistic comments to ads
$ads = $db->query("SELECT id, userId FROM ads ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

$comments = [
    ['ما شاء الله، سلعة فخمة! هل السعر قابل للتفاوض بالمعقول؟'],
    ['الله يرزقك البيعة، واتساب على الرقم لو تكرمت.'],
    ['كم ممشاها بالضبط؟ وهل عليها ضمان؟'],
    ['هل يوجد تنازل أو تبادل مع جيب مع فارق؟'],
    ['تبارك الرحمن، الوصف جميل. من أين شريتها؟'],
];

$commentStmt = $db->prepare("INSERT INTO comments (username, content, adId) VALUES (?, ?, ?)");
$userNames = ['خالد الصنعاني', 'أبو عمار', 'محمد العدني', 'عبدالله', 'سالم اليماني'];
$i = 0;
foreach($ads as $ad){
    try {
        $commentStmt->execute([$userNames[$i % count($userNames)], $comments[$i % count($comments)][0], $ad['id']]);
        $i++;
    } catch(Exception $e){}
}
echo "\nAdded comments.\n";

// Add notifications for some users
$notifStmt = $db->prepare("INSERT INTO notifications (userId, title, content) VALUES (?, ?, ?)");
foreach($users as $u){
    if($u['role'] !== 'admin'){
        try {
            $notifStmt->execute([$u['id'], 'مرحباً بك في حراج اليمن! 🎉', 'يسعدنا انضمامك لأكبر منصة بيع وشراء في اليمن. ابدأ الآن بنشر إعلاناتك مجاناً.']);
        } catch(Exception $e){}
    }
}
echo "Added notifications.\n";

echo "\n=== FINAL COUNTS ===\n";
echo "Users: " . $db->query("SELECT COUNT(*) FROM users")->fetchColumn() . "\n";
echo "Ads: " . $db->query("SELECT COUNT(*) FROM ads")->fetchColumn() . "\n";
echo "Comments: " . $db->query("SELECT COUNT(*) FROM comments")->fetchColumn() . "\n";
echo "Notifications: " . $db->query("SELECT COUNT(*) FROM notifications")->fetchColumn() . "\n";
echo "\nDONE!\n";
