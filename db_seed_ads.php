<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=haraj_db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Clear old ads and comments first to have clean rich data
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("TRUNCATE TABLE comments;");
    $pdo->exec("TRUNCATE TABLE ads;");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    
    // Fetch seeded users
    $userStmt = $pdo->query("SELECT id FROM users LIMIT 5");
    $users = $userStmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($users) < 3) {
        throw new Exception("Users not seeded. Run db_setup.php first.");
    }
    
    $u1 = $users[0]; // أبو راكان للسيارات (admin)
    $u2 = $users[1]; // أحمد محمد (user)
    $u3 = $users[2]; // خالد العتيبي (user)
    $u4 = $users[3]; // محمد العمري (user)
    $u5 = $users[4]; // عبدالله الحميدي (user)
    
    // Mock Data List
    $ads = [
        [
            'userId' => $u1,
            'title' => 'تويوتا لاندكروزر VXR 2024 زيرو بازرعة فل كامل',
            'description' => "للبيع سيارة تويوتا لاندكروزر موديل 2024 VXR فل كامل مواصفات بازرعة.\nالسيارة زيرو ممشى 0 كم تماماً، لم ترخص بعد.\nمواصفات ممتازة:\n- فتحة سقف، شاشات خلفية، مراتب جلد طبيعي بيج.\n- نظام زحف، نظام الرؤية المحيطية 360 درجة، ثلاجة.\n- جنوط 20 بوصة، محرك 6 سلندر توربو مزدوج.\nالسعر نهائي وغير قابل للتفاوض، التواصل للجادين فقط عبر الواتساب أو الاتصال المباشر.",
            'category' => 'cars',
            'city' => 'صنعاء',
            'price' => 95000.00,
            'images' => [
                'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80',
                'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800&q=80'
            ],
            'specifications' => [
                'الماركة' => 'تويوتا',
                'سنة الصنع' => '2024',
                'ناقل الحركة' => 'أوتوماتيك',
                'الممشى' => '0 كم',
                'اللون' => 'أبيض لؤلؤي',
                'الوارد' => 'بازرعة'
            ],
            'carBrand' => 'تويوتا',
            'carYear' => '2024',
            'carTransmission' => 'auto',
            'carMileage' => 0,
            'isPinned' => 1
        ],
        [
            'userId' => $u1,
            'title' => 'هيونداي النترا 2022 نظيفة جداً مجمرك جاهز',
            'description' => "هيونداي النترا موديل 2022 نظيفة خالية من الصدمات والرش التجميلي.\nممشى حقيقي 35 ألف كم فقط.\nمحرك 1600 سي سي، موفرة جداً للبترول.\nمجمركة وجاهزة للترقيم في صنعاء، مكيف ثلج، تحكم دركسون، شاشة تدعم كاربلاي.\nالبيع لحاجة السيولة.",
            'category' => 'cars',
            'city' => 'صنعاء',
            'price' => 14200.00,
            'images' => [
                'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800&q=80',
                'https://images.unsplash.com/photo-1616788494707-ec28f08d05a1?w=800&q=80'
            ],
            'specifications' => [
                'الماركة' => 'هيونداي',
                'سنة الصنع' => '2022',
                'ناقل الحركة' => 'أوتوماتيك',
                'الممشى' => '35,000 كم',
                'اللون' => 'رمادي ميتاليك'
            ],
            'carBrand' => 'هيونداي',
            'carYear' => '2022',
            'carTransmission' => 'auto',
            'carMileage' => 35000,
            'isPinned' => 0
        ],
        [
            'userId' => $u2,
            'title' => 'شقة فاخرة للإيجار السنوي - حي حدة السكني',
            'description' => "شقة سكنية راقية للإيجار في أرقى أحياء صنعاء (حي حدة) بالقرب من الخدمات والمنظمات.\nتتكون الشقة من:\n- 4 غرف واسعة\n- مجلس كبير مستقل مع حمامه الخاص\n- صالة عائلية فسيحة\n- مطبخ راكب بتشطيب تركي حديث\n- 3 حمامات إجمالاً\n- موقف سيارة مغطى خاص بالدور الأرضي\nعقد الإيجار سنوي والدفع شهري، يفضل العائلات الصغيرة.",
            'category' => 'realestate',
            'city' => 'صنعاء',
            'price' => 600.00,
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80'
            ],
            'specifications' => [
                'نوع العقار' => 'شقة سكنية',
                'عدد الغرف' => '4 غرف وصالة',
                'نوع العقد' => 'إيجار',
                'الحي' => 'حدة خلف مركز الكمبيوتر'
            ],
            'propertyType' => 'apartment',
            'propertyRooms' => '4',
            'propertyContract' => 'rent',
            'isPinned' => 0
        ],
        [
            'userId' => $u3,
            'title' => 'آيفون 15 برو ماكس 256 جيجا كرت كرتون جاهز عدن',
            'description' => "للبيع جهاز iPhone 15 Pro Max سعة 256 جيجابايت.\nاللون: تيتانيوم طبيعي (Natural Titanium).\nالجهاز كرت لم يفتح من الكرتون إطلاقاً (شريط اللاصق الوكالة سليم).\nنسخة الشرق الأوسط شريحتين، ضمان سنتين.\nمتواجد في عدن (التسليم يداً بيد والتأكد قبل الدفع).",
            'category' => 'electronics',
            'city' => 'عدن',
            'price' => 1150.00,
            'images' => [
                'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&q=80',
                'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&q=80'
            ],
            'specifications' => [
                'النوع' => 'هواتف ذكية',
                'الماركة' => 'Apple / آبل',
                'الموديل' => 'iPhone 15 Pro Max',
                'المساحة التخزينية' => '256 جيجابايت',
                'حالة البطارية' => '100% (جديد)'
            ],
            'isPinned' => 0
        ],
        [
            'userId' => $u2,
            'title' => 'مواشي بلدي ممتازة وأضاحي العيد من مزارع ذمار',
            'description' => "مواشي بلدي (غنم خرفان وتيوس ذمارية) تربية بيتي ممتازة جداً وأوزان طيبة.\nتغذية طبيعية 100% بدون أي هرمونات أو إضافات ضارة.\nالأسعار تبدأ من 150 ألف ريال يمني وتختلف حسب الحجم والوزن.\nيتوفر لدينا خدمة التوصيل مجاناً داخل مدينة ذمار وصنعاء للكميات.",
            'category' => 'livestock',
            'city' => 'ذمار',
            'price' => 250000.00,
            'images' => [
                'https://images.unsplash.com/photo-1484557052118-f32bd25b45b5?w=800&q=80',
                'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=800&q=80'
            ],
            'specifications' => [
                'النوع' => 'أغنام ومواشي',
                'العمر' => '6 أشهر إلى سنة',
                'التغذية' => 'طبيعية (شعير وبرسيم)'
            ],
            'isPinned' => 0
        ],
        [
            'userId' => $u1,
            'title' => 'مرسيدس E350 موديل 2019 بانوراما فل أوبشن لقطة',
            'description' => "مرسيدس بنز E350 موديل 2019 وارد أمريكا، بحالة الوكالة نظيفة جداً.\nفتحة سقف بانوراما، رادار أمامي، تحديد مسار، بروجكتر، سماعات برومستر، مقاعد جلد بيج كهربائية مع ذاكرة، إضاءة داخلية 64 لون.\nممشى السيارة 52 ألف ميل فقط.\nخالية تماماً من العيوب والأعطال ومفحوصة بالكامل.",
            'category' => 'cars',
            'city' => 'صنعاء',
            'price' => 31000.00,
            'images' => [
                'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=800&q=80',
                'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800&q=80'
            ],
            'specifications' => [
                'الماركة' => 'مرسيدس',
                'سنة الصنع' => '2019',
                'ناقل الحركة' => 'أوتوماتيك',
                'الممشى' => '52,000 ميل',
                'اللون' => 'أسود ملكي'
            ],
            'carBrand' => 'مرسيدس',
            'carYear' => '2019',
            'carTransmission' => 'auto',
            'carMileage' => 83000,
            'isPinned' => 0
        ],
        [
            'userId' => $u4,
            'title' => 'أثاث منزلي متكامل للبيع لدواعي السفر - تعز',
            'description' => "طقم كنب تركي فاخر يتسع لـ 9 أشخاص + طاولة طعام خشب زان 6 كراسي + غرف نوم خشب ماليزي ممتازة.\nالأثاث مستعمل خفيف وبحالة ممتازة وخالي من أي عيوب أو كسور.\nالبيع كامل أو مفرق، والأسعار مناسبة جداً.\nالموقع: تعز، حي المسبح.",
            'category' => 'furniture',
            'city' => 'تعز',
            'price' => 1800.00,
            'images' => [
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&q=80',
                'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&q=80'
            ],
            'specifications' => [
                'نوع الأثاث' => 'كنب وطاولات وغرف نوم',
                'الحالة' => 'مستعمل بحالة ممتازة',
                'الصناعة' => 'تركي / ماليزي'
            ],
            'isPinned' => 0
        ],
        [
            'userId' => $u5,
            'title' => 'فيلا للبيع في حي الصافية بتصميم ملكي فاخر',
            'description' => "فيلا فخمة للبيع في صنعاء - الصافية، مبنية على مساحة 8 لبن حر.\nتتألف من دورين وبدروم وقرّاج يتسع لـ 3 سيارات.\nتشطيب الفيلا سوبر لوكس، ديكورات جبسية فاخرة، أرضيات رخام.\nتحتوي على ملحق خارجي مستقل وحوش واسع.\nلمزيد من التفاصيل يرجى التواصل.",
            'category' => 'realestate',
            'city' => 'صنعاء',
            'price' => 380000.00,
            'images' => [
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80'
            ],
            'specifications' => [
                'نوع العقار' => 'فيلا مستقلة',
                'المساحة' => '8 لبن حر',
                'عدد الأدوار' => 'دورين وبدروم',
                'الحالة' => 'جديد تشطيب سوبر لوكس'
            ],
            'propertyType' => 'villa',
            'propertyRooms' => '5+',
            'propertyContract' => 'sell',
            'isPinned' => 0
        ],
        [
            'userId' => $u3,
            'title' => 'مطلوب مهندس شبكات وسيرفرات لشركة اتصالات في عدن',
            'description' => "تعلن شركة كبرى في عدن عن حاجتها لمهندس شبكات وسيرفرات ذو خبرة لا تقل عن 3 سنوات.\nالمهارات المطلوبة:\n- إدارة سيرفرات Linux & Windows.\n- إعداد وحماية الراوترات والسويتشات Cisco.\n- خبرة في أنظمة المراقبة والنسخ الاحتياطي.\nالدوام كامل والراتب يحدد بعد المقابلة ويوجد حوافز وسكن للموظفين من خارج عدن.",
            'category' => 'jobs',
            'city' => 'عدن',
            'price' => 0.00,
            'images' => [
                'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&q=80'
            ],
            'specifications' => [
                'نوع الوظيفة' => 'دوام كامل',
                'الخبرة المطلوبة' => '3 سنوات فما فوق',
                'المكان' => 'عدن - المنصورة'
            ],
            'isPinned' => 0
        ],
        [
            'userId' => $u2,
            'title' => 'خدمات تركيب وصيانة ألواح الطاقة الشمسية المتكاملة',
            'description' => "نقوم بتصميم وتركيب وصيانة منظومات الطاقة الشمسية للمنازل والمزارع والشركات.\n- تركيب ألواح شمسية بجودة عالية وضمان 5 سنوات.\n- تركيب وبرمجة الإنفرترات والمنظمات.\n- فحص وتجديد البطاريات وتمديد الأسلاك بطريقة هندسية آمنة.\nنخبة من المهندسين والفنيين ذوي الخبرة الطويلة في هذا المجال، أسعارنا منافسة.",
            'category' => 'services',
            'city' => 'صنعاء',
            'price' => 0.00,
            'images' => [
                'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=800&q=80',
                'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?w=800&q=80'
            ],
            'specifications' => [
                'نوع الخدمة' => 'طاقة متجددة وصيانة هندسية',
                'مكان تقديم الخدمة' => 'صنعاء والمحافظات المجاورة',
                'الضمان' => 'يتوفر ضمان على التركيب'
            ],
            'isPinned' => 0
        ]
    ];
    
    // Insert Ads
    $insStmt = $pdo->prepare("INSERT INTO ads (userId, title, description, category, city, price, images, specifications, carBrand, carYear, carTransmission, carMileage, propertyType, propertyRooms, propertyContract, isPinned) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($ads as $ad) {
        $insStmt->execute([
            $ad['userId'],
            $ad['title'],
            $ad['description'],
            $ad['category'],
            $ad['city'],
            $ad['price'],
            json_encode($ad['images']),
            json_encode($ad['specifications']),
            $ad['carBrand'] ?? null,
            $ad['carYear'] ?? null,
            $ad['carTransmission'] ?? null,
            $ad['carMileage'] ?? null,
            $ad['propertyType'] ?? null,
            $ad['propertyRooms'] ?? null,
            $ad['propertyContract'] ?? null,
            $ad['isPinned']
        ]);
        
        $newAdId = $pdo->lastInsertId();
        
        // Add mock comments for this ad
        $comStmt = $pdo->prepare("INSERT INTO comments (adId, username, content) VALUES (?, ?, ?)");
        $comStmt->execute([$newAdId, 'خالد العتيبي', 'ما شاء الله تبارك الرحمن، سلعة ممتازة الله يبارك لك في حلالك.']);
        $comStmt->execute([$newAdId, 'أحمد محمد', 'كم السعر النهائي بالريال السعودي يا غالي؟ وهل تقبل البدل؟']);
    }
    
    echo "Rich sample advertisements and mock comments seeded successfully!\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
