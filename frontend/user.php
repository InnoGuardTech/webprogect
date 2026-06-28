<?php
$pageTitle = 'الملف الشخصي - حراج اليمن';
require_once 'includes/header.php';
?>
    <style>
        .profile-header {
            background: linear-gradient(135deg, rgba(0,51,102,0.9) 0%, rgba(0,77,153,0.9) 100%), url('https://images.unsplash.com/photo-1557683316-973673baf926?w=1200&q=80') center/cover;
            color: white;
            padding: 4rem 1.5rem;
            text-align: center;
            border-radius: var(--radius-xl);
            margin-bottom: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,77,153,0.2);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(0,0,0,0) 80%);
            pointer-events: none;
        }
        .profile-avatar {
            width: 108px;
            height: 108px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 3.2rem;
            margin-bottom: 1rem;
            border: 3px solid var(--accent);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: var(--transition);
        }
        .profile-avatar:hover {
            transform: scale(1.05);
        }
        .profile-stats {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-top: 1.75rem;
        }
        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(5px);
            padding: 0.6rem 1.5rem;
            border-radius: var(--radius-md);
            border: 1px solid rgba(255,255,255,0.12);
            min-width: 140px;
        }
        .stat-val {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--accent);
        }
        .stat-lbl {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 700;
            margin-top: 2px;
        }
        .profile-content {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.25rem;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 992px) {
            .profile-content { grid-template-columns: 1fr; }
        }
    </style>

    <div style="max-width: 1200px; margin: 1.5rem auto; padding: 0 1.25rem;">
        <div id="loading" style="text-align:center; padding: 5rem; color:var(--text-muted); font-weight:bold;">
            جاري تحميل الملف الشخصي للمستخدم... ⏳
        </div>

        <div id="profile-container" class="hidden animate-fade-in">
            <div class="profile-header">
                <div class="profile-avatar">👤</div>
                <h1 id="p-name" style="margin:0 0 0.5rem 0; font-weight:900; font-size:2.2rem; text-shadow:0 2px 10px rgba(0,0,0,0.3);"></h1>
                <div style="color:rgba(255,255,255,0.9); font-weight:700; font-size:0.95rem; display:flex; align-items:center; justify-content:center; gap:8px;">
                    <span style="background:var(--accent); color:#1a1a2e; padding:2px 8px; border-radius:12px; font-size:0.75rem; font-weight:900;">موثق ✓</span>
                    عضو منذ <span id="p-date"></span>
                </div>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <div id="owner-actions" style="display:none; margin-top:1.5rem;">
                    <a href="settings.php" style="background:rgba(255,255,255,0.15); backdrop-filter:blur(5px); border:1px solid rgba(255,255,255,0.3); color:white; padding:0.5rem 1.5rem; border-radius:20px; font-weight:bold; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all 0.3s;">
                        ⚙️ إعدادات الحساب
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-val" id="p-rating"></span>
                        <span class="stat-lbl">تقييم الأعضاء</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-val" id="p-ads-count">0</span>
                        <span class="stat-lbl">السلع المنشورة</span>
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <!-- User Ads Section -->
                <div>
                    <h3 style="margin-top:0; color:var(--primary); font-weight:900; margin-bottom:1rem; display:flex; align-items:center; gap:6px;">📦 إعلانات المستخدم</h3>
                    <div id="p-ads" class="ad-list"></div>
                </div>
                
                <!-- Ratings & Reviews Section -->
                <div>
                    <h3 style="margin-top:0; color:var(--primary); font-weight:900; margin-bottom:1rem; display:flex; align-items:center; gap:6px;">⭐ التقييمات والآراء</h3>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="premium-card" style="padding:1.25rem; margin-bottom:1.25rem;" id="review-form-container">
                        <h4 style="margin:0 0 1rem 0; color:var(--primary); font-size:0.95rem; font-weight:800; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem;">أضف تقييمك للتاجر</h4>
                        <form onsubmit="addReview(event)">
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; font-size:0.78rem; font-weight:800; color:var(--text-muted); margin-bottom:0.4rem;">التقييم العام</label>
                                <select id="r-rating" class="input-premium" style="font-size:0.8rem;">
                                    <option value="5">⭐⭐⭐⭐⭐ ممتاز وأنصح بالتعامل معه</option>
                                    <option value="4">⭐⭐⭐⭐ جيد جداً</option>
                                    <option value="3">⭐⭐⭐ جيد ومقبول</option>
                                    <option value="2">⭐⭐ لديه بعض السلبيات</option>
                                    <option value="1">⭐ سيء ولا أنصح بالتعامل معه</option>
                                </select>
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; font-size:0.78rem; font-weight:800; color:var(--text-muted); margin-bottom:0.4rem;">اكتب رأيك بأمانة</label>
                                <textarea id="r-content" class="input-premium" required placeholder="كيف كانت مصداقية السلعة وسرعة التجاوب؟" style="font-size:0.8rem; min-height:80px; resize:vertical;"></textarea>
                            </div>
                            <button type="submit" class="btn-primary" style="width:100%; padding:0.55rem; font-size:0.82rem;">نشر التقييم بنجاح</button>
                        </form>
                    </div>
                    <?php endif; ?>

                    <div id="p-reviews" style="display:flex; flex-direction:column; gap:0.75rem;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core App JS Utilities -->
    <script src="assets/js/app.js"></script>
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('id');

        async function init() {
            if (!userId) {
                document.getElementById('loading').innerText = 'رقم المستخدم غير صحيح';
                return;
            }

            // Hide review form if viewing own profile
            <?php if (isset($_SESSION['user_id'])): ?>
            if (userId == <?php echo $_SESSION['user_id']; ?>) {
                const rf = document.getElementById('review-form-container');
                if(rf) rf.style.display = 'none';
                
                const oa = document.getElementById('owner-actions');
                if(oa) oa.style.display = 'block';
            }
            <?php endif; ?>

            try {
                const res = await apiRequest(`user&action=profile&id=${userId}`);
                const data = res.data;

                document.getElementById('loading').classList.add('hidden');
                document.getElementById('profile-container').classList.remove('hidden');

                document.title = `الملف الشخصي لـ ${data.user.name} - حراج اليمن`;
                document.getElementById('p-name').innerText = data.user.name;
                document.getElementById('p-date').innerText = data.user.joinedDate;
                document.getElementById('p-rating').innerText = parseFloat(data.user.rating).toFixed(1) + ' ★';
                document.getElementById('p-ads-count').innerText = data.ads.length;

                // Render Ads as premium rows
                const adsContainer = document.getElementById('p-ads');
                if (data.ads.length === 0) {
                    adsContainer.innerHTML = '<div style="text-align:center; color:var(--text-muted); padding:3rem; font-weight:bold;">لا توجد إعلانات منشورة لهذا المستخدم بعد.</div>';
                } else {
                    adsContainer.innerHTML = data.ads.map(ad => `
                        <a href="ad.php?id=${ad.id}" class="ad-row animate-fade-in">
                            <div class="ad-row-main">
                                <img class="ad-row-thumb" src="${ad.image}" alt="${ad.title}">
                                <div class="ad-row-content">
                                    <h3 class="ad-row-title">${ad.title}</h3>
                                    <div class="ad-row-meta">
                                        <div class="ad-row-meta-item">⏱️ <span>${ad.date}</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="ad-row-side">
                                <div class="ad-row-price">${ad.price}</div>
                                <div class="ad-row-city">📍 ${ad.city}</div>
                            </div>
                        </a>
                    `).join('');
                }

                // Render Reviews
                const revContainer = document.getElementById('p-reviews');
                if (data.reviews.length === 0) {
                    revContainer.innerHTML = '<div style="text-align:center; color:var(--text-muted); padding:2rem; font-weight:bold;">لا توجد تقييمات أو تعليقات للتاجر بعد.</div>';
                } else {
                    revContainer.innerHTML = data.reviews.map(r => `
                        <div class="premium-card" style="padding:1rem;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:0.4rem; font-size:0.75rem;">
                                <strong style="color:var(--primary); font-weight:800;">${r.author}</strong>
                                <span style="color:var(--text-muted); font-weight:700;">${r.date}</span>
                            </div>
                            <div style="color:#f59e0b; margin-bottom:0.4rem; font-size:0.8rem;">${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</div>
                            <div style="font-size:0.82rem; font-weight:700; line-height:1.5;">${r.content}</div>
                        </div>
                    `).join('');
                }

            } catch (err) {
                document.getElementById('loading').innerHTML = '<div style="color:red; font-size:1.2rem; font-weight:bold;">❌ المستخدم المطلوب غير موجود أو موقوف من قبل الإدارة.</div>';
            }
        }

        async function addReview(e) {
            e.preventDefault();
            const rating = document.getElementById('r-rating').value;
            const content = document.getElementById('r-content').value;
            
            try {
                await apiRequest('user', 'POST', { action: 'add_review', target_id: userId, rating: rating, content: content });
                document.getElementById('r-content').value = '';
                showToast('تمت إضافة تقييمك للمستخدم بنجاح');
                init();
            } catch(err) {}
        }

        document.addEventListener('DOMContentLoaded', init);
    </script>
<?php require_once 'includes/footer.php'; ?>
