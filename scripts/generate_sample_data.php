<?php
// scripts/generate_sample_data.php

require_once __DIR__ . '/../config.php';
$db = getDBConnection();

echo "Starting to insert sample data...\n";

// 1. Insert Categories
$categories = [
    ['name' => 'السيارات', 'icon' => '🚗'],
    ['name' => 'العقارات', 'icon' => '🏠'],
    ['name' => 'الأجهزة', 'icon' => '📱'],
    ['name' => 'الأثاث', 'icon' => '🪑'],
    ['name' => 'الخدمات', 'icon' => '🛠️']
];

foreach ($categories as $cat) {
    try {
        $stmt = $db->prepare("INSERT IGNORE INTO categories (name, icon) VALUES (?, ?)");
        $stmt->execute([$cat['name'], $cat['icon']]);
    } catch(Exception $e) {}
}
echo "Categories inserted.\n";

// 2. Insert Users
$users = [
    ['name' => 'أحمد اليمني', 'phone' => '777000111', 'password' => '123456', 'role' => 'user'],
    ['name' => 'معرض الفخامة', 'phone' => '733000222', 'password' => '123456', 'role' => 'user'],
    ['name' => 'عقارات صنعاء', 'phone' => '711000333', 'password' => '123456', 'role' => 'user'],
    ['name' => 'المدير العام', 'phone' => '777999888', 'password' => '123456', 'role' => 'admin']
];

foreach ($users as $u) {
    try {
        $hashed = password_hash($u['password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT IGNORE INTO users (name, phone, password, role, joinedDate, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$u['name'], $u['phone'], $hashed, $u['role'], date('Y-m'), 5.0]);
    } catch(Exception $e) {}
}
echo "Users inserted.\n";

// 3. Insert Ads
$ads = [
    [
        'user_id' => 2,
        'category_id' => 1,
        'title' => 'تويوتا لاندكروزر 2023 وكالة',
        'price' => 25000000,
        'city' => 'صنعاء',
        'description' => "سيارة تويوتا لاندكروزر 2023 VXR نظيفة جداً، خالية من الصدمات، الممشى قليل، السعر قابل للتفاوض بالمعقول.",
        'image' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=800',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'user_id' => 3,
        'category_id' => 2,
        'title' => 'فلة فاخرة للبيع في حدة',
        'price' => 150000000,
        'city' => 'صنعاء',
        'description' => "فلة تشطيب سوبر لوكس في حي حدة الراقي، 5 غرف نوم، 4 حمامات، حوش كبير وكراج سيارات.",
        'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'user_id' => 1,
        'category_id' => 3,
        'title' => 'ايفون 14 برو ماكس',
        'price' => 500000,
        'city' => 'عدن',
        'description' => "ايفون 14 برو ماكس 256 جيجا، بطارية 95%، مع الكرتون وجميع الملحقات الأصلية.",
        'image' => 'https://images.unsplash.com/photo-1678652197831-2d180705cd2c?auto=format&fit=crop&w=800',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'user_id' => 2,
        'category_id' => 1,
        'title' => 'لكزس 2020 وارد أمريكي',
        'price' => 12000000,
        'city' => 'إب',
        'description' => "لكزس ES350 موديل 2020، لون لؤلؤي، صدمة خفيفة جداً في الصدام الأمامي ومصلحة بالكامل.",
        'image' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?auto=format&fit=crop&w=800',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ],
    [
        'user_id' => 1,
        'category_id' => 4,
        'title' => 'طقم كنب تركي فاخر جديد',
        'price' => 150000,
        'city' => 'صنعاء',
        'description' => "طقم كنب تركي 7 مقاعد، قماش مخمل ضد الماء، خشب زان صلب، متوفر بألوان متعددة.",
        'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=800',
        'status' => 'active',
        'created_at' => date('Y-m-d H:i:s')
    ]
];

foreach ($ads as $ad) {
    try {
        $stmt = $db->prepare("INSERT INTO ads (user_id, category_id, title, price, city, description, image, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ad['user_id'], $ad['category_id'], $ad['title'], $ad['price'], $ad['city'], $ad['description'], $ad['image'], $ad['status'], $ad['created_at']]);
    } catch(Exception $e) {}
}
echo "Ads inserted.\n";

echo "Sample data generation complete!\n";
