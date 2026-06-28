<?php
$pageTitle = 'تفاصيل الإعلان - حراج اليمن';
require_once 'includes/header.php';
?>
    <style>
        .ad-container {
            max-width: 1200px;
            margin: 1.5rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 992px) {
            .ad-container { grid-template-columns: 1fr; }
        }
        
        /* Breadcrumbs style */
        .breadcrumbs {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .breadcrumbs a {
            color: var(--primary);
            text-decoration: none;
        }
        .breadcrumbs a:hover {
            text-decoration: underline;
        }

        .ad-title-area {
            margin-bottom: 1rem;
        }
        .ad-title-text {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--secondary);
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ad-metabar {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 700;
            flex-wrap: wrap;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
        }

        .image-gallery {
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .main-image-container {
            aspect-ratio: 16/9;
            background-color: #eee;
            position: relative;
        }
        .main-image-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .thumbnail-list {
            display: flex;
            gap: 8px;
            padding: 8px;
            overflow-x: auto;
            border-top: 1px solid var(--border-color);
            background-color: var(--bg-color);
        }
        .thumbnail-list img {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }
        .thumbnail-list img:hover, .thumbnail-list img.active {
            border-color: var(--primary);
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.75rem;
            background-color: var(--bg-color);
            padding: 1rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            margin-bottom: 1.5rem;
        }
        .spec-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .spec-item .label {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: 700;
        }
        .spec-item .val {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--primary);
        }

        /* Warning Card like Saudi Haraj */
        .warning-card {
            background-color: #fff9e6;
            border: 1px solid #ffe8a3;
            border-radius: var(--radius-md);
            padding: 1rem;
            color: #856404;
            font-size: 0.75rem;
            font-weight: 700;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .seller-card {
            text-align: center;
        }
        .seller-avatar {
            width: 64px;
            height: 64px;
            background-color: var(--bg-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            border: 2px solid var(--primary);
        }

        .btn-whatsapp {
            background-color: #25D366;
            color: white !important;
            font-weight: 900;
            padding: 0.65rem 1.25rem;
            border-radius: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-whatsapp:hover {
            background-color: #1ebd59;
            transform: translateY(-1px);
        }

        .btn-chat {
            background-color: var(--primary);
            color: white !important;
            font-weight: 900;
            padding: 0.65rem 1.25rem;
            border-radius: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            width: 100%;
            cursor: pointer;
            font-size: 0.85rem;
            font-family: inherit;
            transition: all 0.2s;
        }
        .btn-chat:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Comments design */
        .comment-box {
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }
        .comment-box:last-child {
            border-bottom: none;
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.4rem;
            font-size: 0.8rem;
        }
        .comment-user {
            color: var(--primary);
            font-weight: 800;
            text-decoration: none;
        }
        .comment-date {
            color: var(--text-muted);
            font-weight: 600;
        }
        .comment-body {
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.6;
            white-space: pre-wrap;
        }
    </style>

    <!-- Ad loading placeholder -->
    <div id="loading-spinner" style="text-align:center; padding:5rem; color:var(--text-muted); font-weight:bold;">
        جاري تحميل تفاصيل السلعة... ⏳
    </div>

    <!-- Main Detail Layout -->
    <div class="ad-container hidden" id="ad-view-content">
        
        <!-- Left Side Column (Main description and images) -->
        <main class="animate-fade-in">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <a href="index.php">الرئيسية</a> &gt; 
                <span id="breadcrumb-category"></span> &gt;
                <span id="breadcrumb-title" style="color:var(--text-muted);"></span>
            </div>

            <!-- Ad title & Fav button -->
            <div class="ad-title-area">
                <h1 class="ad-title-text">
                    <span id="ad-main-title"></span>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button onclick="toggleFavorite()" id="fav-btn" style="background:none; border:none; font-size:1.5rem; cursor:pointer; padding:0; line-height:1;" title="حفظ الإعلان">🤍</button>
                    <?php endif; ?>
                </h1>
                <div id="owner-actions" class="hidden" style="margin-top:1rem; gap:0.5rem;">
                    <button onclick="deleteMyAd()" style="background:#ef4444; color:white; border:none; padding:0.5rem 1.2rem; border-radius:var(--radius-sm); cursor:pointer; font-weight:bold; font-family:inherit; font-size:0.9rem;">🗑️ حذف الإعلان</button>
                </div>
            </div>

            <!-- Metabar (location, author, time, ad id) -->
            <div class="ad-metabar">
                <span id="meta-location">📍 </span>
                <span id="meta-author">👤 </span>
                <span id="meta-date">⏱️ </span>
                <span id="meta-id">🔢 رقم الإعلان: </span>
                <span id="meta-views">👁️ </span>
            </div>

            <!-- Ad Image Gallery slider -->
            <div class="image-gallery">
                <div class="main-image-container">
                    <img id="ad-main-img" src="" alt="Main Ad Image">
                </div>
                <div class="thumbnail-list" id="ad-thumbnails">
                    <!-- Dynamic thumbnails -->
                </div>
            </div>

            <!-- Specs Grid -->
            <div id="ad-specs-container" class="specs-grid hidden">
                <!-- Dynamic specifications -->
            </div>

            <!-- Detailed description -->
            <div class="premium-card" style="margin-bottom:1.5rem;">
                <h3 style="margin:0 0 1rem 0; color:var(--primary); font-size:1.05rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem;">تفاصيل السلعة 📝</h3>
                <p id="ad-main-description" style="line-height:1.8; font-weight:600; font-size:0.95rem; margin:0; white-space:pre-wrap;"></p>
            </div>
        </main>

        <!-- Right Side Column (Seller Card & Warnings) -->
        <aside class="animate-fade-in">
            <!-- Fraud Warning Card like Saudi Haraj -->
            <div class="warning-card">
                ⚠️ **تنبيه أمان:** تجنب الاحتيال بالتعامل يداً بيد فقط! لا تقم بتحويل مبالغ مالية مسبقة تحت أي ظرف. حراج اليمن لا يتدخل في عمليات الدفع أو الشحن.
            </div>

            <!-- Price Card -->
            <div class="premium-card" style="text-align:center; padding:1.25rem; margin-bottom:1rem; border-right:4px solid var(--secondary);">
                <div style="font-size:0.8rem; color:var(--text-muted); font-weight:700; margin-bottom:4px;">السعر المطلوب 💰</div>
                <div id="ad-price-display" style="font-size:1.6rem; font-weight:900; color:var(--secondary);"></div>
            </div>

            <!-- Seller Information Card -->
            <div class="premium-card seller-card" style="margin-bottom:1.5rem;">
                <div class="seller-avatar">👤</div>
                <h3 id="seller-username" style="margin:0 0 0.25rem 0; font-size:1.05rem;"></h3>
                
                <!-- Stars rating system -->
                <div id="seller-stars" style="font-size:0.95rem; color:#f59e0b; margin-bottom:0.25rem;"></div>
                <div style="font-size:0.75rem; color:var(--text-muted); font-weight:700; margin-bottom:1.25rem;">عضو منذ <span id="seller-joined"></span></div>
                
                <!-- Quick Contact buttons -->
                <a href="#" id="whatsapp-link" class="btn-whatsapp" target="_blank">
                    <span>💬</span> تواصل عبر واتساب
                </a>
                
                <button onclick="startPrivateChat()" class="btn-chat">
                    <span>✉️</span> مراسلة خاصة بالبائع
                </button>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <button onclick="reportAd()" style="background:transparent; color:#ef4444; border:1px solid #ef4444; font-weight:800; padding:0.5rem 1rem; border-radius:20px; margin-top:1rem; width:100%; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:6px; font-size:0.8rem; font-family:inherit; transition:all 0.2s;">
                    <span>🚩</span> إبلاغ عن إعلان مخالف
                </button>
                <?php endif; ?>
            </div>

            <!-- Comments Area -->
            <div class="premium-card">
                <h3 style="margin:0 0 1rem 0; color:var(--primary); font-size:1rem; border-bottom:1px solid var(--border-color); padding-bottom:0.5rem;">الردود والتعليقات 💬</h3>
                
                <div id="ad-comments-list" style="display:flex; flex-direction:column; margin-bottom:1rem; max-height:400px; overflow-y:auto; padding-left:4px;">
                    <!-- Dynamic comments list -->
                </div>

                <!-- Add Comment Form -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form onsubmit="submitComment(event)" style="display:flex; gap:0.5rem; flex-direction:column;">
                        <input type="text" id="comment-content" class="input-premium" placeholder="اكتب ردك أو استفسارك هنا..." required style="font-size:0.8rem;">
                        <button type="submit" class="btn-chat" style="padding:0.45rem; font-size:0.8rem; justify-content:center;">إرسال الرد</button>
                    </form>
                <?php else: ?>
                    <div style="text-align:center; padding:0.75rem; background-color:var(--bg-color); border-radius:var(--radius-md); font-size:0.75rem; font-weight:700;">
                        يرجى <a href="auth.php" style="color:var(--primary);">تسجيل الدخول</a> للرد على هذا الإعلان.
                    </div>
                <?php endif; ?>
            </div>
        </aside>

    </div>

    <!-- Core App JS Utilities -->
    <script src="assets/js/app.js"></script>
    <script>
        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
        const urlParams = new URLSearchParams(window.location.search);
        const adId = urlParams.get('id');
        let currentAd = null;

        async function init() {
            if (!adId) {
                document.getElementById('loading-spinner').innerText = 'خطأ: رقم السلعة غير صالح.';
                return;
            }

            try {
                const res = await apiRequest(`ads&id=${adId}`);
                const ad = res.data;
                currentAd = ad;

                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('ad-view-content').classList.remove('hidden');

                // Page titles & SEO metadata
                document.title = `${ad.title} - حراج اليمن`;
                
                document.getElementById('breadcrumb-category').innerHTML = `<a href="index.php" onclick="window.location.href='index.php'; return false;">${ad.category}</a>`;
                document.getElementById('breadcrumb-title').innerText = ad.title.length > 30 ? ad.title.substring(0, 30) + '...' : ad.title;

                document.getElementById('ad-main-title').innerText = ad.title;
                document.getElementById('ad-main-description').innerText = ad.description;
                document.getElementById('ad-price-display').innerText = ad.formattedPrice;

                // Sync Metabar
                document.getElementById('meta-location').innerHTML = `📍 ${ad.city}`;
                document.getElementById('meta-author').innerHTML = `👤 ${ad.userName}`;
                document.getElementById('meta-date').innerHTML = `⏱️ ${ad.formattedDate}`;
                document.getElementById('meta-id').innerHTML = `🔢 رقم الإعلان: ${ad.id}`;
                document.getElementById('meta-views').innerHTML = `👁️ ${ad.views} مشاهدة`;

                // Set Favorite button state
                const favBtn = document.getElementById('fav-btn');
                if (favBtn) {
                    favBtn.innerText = ad.isFavorite ? '❤️' : '🤍';
                }

                // Gallery Image load
                const mainImg = document.getElementById('ad-main-img');
                const thumbContainer = document.getElementById('ad-thumbnails');
                thumbContainer.innerHTML = '';

                if (ad.images && ad.images.length > 0) {
                    mainImg.src = ad.images[0];
                    ad.images.forEach((img, idx) => {
                        const thumb = document.createElement('img');
                        thumb.src = img;
                        if (idx === 0) thumb.className = 'active';
                        thumb.onclick = () => {
                            document.querySelectorAll('.thumbnail-list img').forEach(t => t.classList.remove('active'));
                            thumb.classList.add('active');
                            mainImg.src = img;
                        };
                        thumbContainer.appendChild(thumb);
                    });
                } else {
                    mainImg.src = 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=600&q=80';
                }

                // Specs loader
                const specsGrid = document.getElementById('ad-specs-container');
                specsGrid.innerHTML = '';
                if (ad.specifications && Object.keys(ad.specifications).length > 0) {
                    let hasSpecs = false;
                    for (const [key, val] of Object.entries(ad.specifications)) {
                        if (val && val !== 'الكل') {
                            hasSpecs = true;
                            const el = document.createElement('div');
                            el.className = 'spec-item';
                            el.innerHTML = `<span class="label">${key}</span><span class="val">${val}</span>`;
                            specsGrid.appendChild(el);
                        }
                    }
                    if (hasSpecs) specsGrid.classList.remove('hidden');
                }

                // Seller Card details
                document.getElementById('seller-username').innerHTML = `<a href="user.php?id=${ad.userId}" style="color:inherit; text-decoration:none;">${ad.userName}</a>`;
                document.getElementById('seller-stars').innerHTML = getStarsHTML(ad.userRating) + ` (${parseFloat(ad.userRating).toFixed(1)})`;
                document.getElementById('seller-joined').innerText = ad.joinedDate;

                // Whatsapp Link generator
                let phone = ad.userPhone;
                if (phone.startsWith('0')) phone = phone.substring(1);
                if (phone.length === 9) phone = '967' + phone;
                document.getElementById('whatsapp-link').href = `https://wa.me/${phone}?text=مرحباً بخصوص إعلانك (${ad.title}) في حراج اليمن:`;

                // Render Comments
                renderComments(ad.comments);

                // Show owner actions
                if (currentUserId && currentUserId == ad.userId) {
                    const ownerActions = document.getElementById('owner-actions');
                    ownerActions.classList.remove('hidden');
                    ownerActions.style.display = 'flex';
                }

            } catch (err) {
                document.getElementById('loading-spinner').innerHTML = '<div style="color:red; font-size:1.2rem;">❌ الإعلان غير موجود أو تم إزالته من قبل الإدارة.</div>';
            }
        }

        function getStarsHTML(rating) {
            const r = Math.round(rating || 5);
            let stars = '';
            for(let i=0; i<5; i++) {
                stars += i < r ? '★' : '☆';
            }
            return stars;
        }

        function renderComments(comments) {
            const list = document.getElementById('ad-comments-list');
            list.innerHTML = '';
            
            if (!comments || comments.length === 0) {
                list.innerHTML = '<div style="color:var(--text-muted); text-align:center; font-size:0.8rem; padding:1.5rem 0;">لا توجد ردود بعد.</div>';
                return;
            }

            comments.forEach(c => {
                const box = document.createElement('div');
                box.className = 'comment-box';
                box.innerHTML = `
                    <div class="comment-header">
                        <a href="user.php?username=${encodeURIComponent(c.username)}" class="comment-user">${c.username}</a>
                        <span class="comment-date">${c.date}</span>
                    </div>
                    <div class="comment-body">${c.content}</div>
                `;
                list.appendChild(box);
            });
        }

        async function submitComment(e) {
            e.preventDefault();
            const input = document.getElementById('comment-content');
            const content = input.value.trim();
            if (!content) return;

            try {
                await apiRequest('ads', 'POST', { action: 'add_comment', ad_id: adId, content: content });
                input.value = '';
                init(); // reload data
            } catch(e) {}
        }

        async function toggleFavorite() {
            try {
                const res = await apiRequest('ads', 'POST', { action: 'toggle_favorite', ad_id: adId });
                document.getElementById('fav-btn').innerText = res.data.isFavorite ? '❤️' : '🤍';
                showToast(res.message);
            } catch(e) {}
        }

        async function startPrivateChat() {
            <?php if (!isset($_SESSION['user_id'])): ?>
                window.location.href = 'auth.php';
                return;
            <?php endif; ?>

            try {
                const res = await apiRequest('chat', 'POST', { action: 'send', ad_id: adId, text: 'مرحباً، أنا مهتم بهذه السلعة.' });
                window.location.href = `messages.php?thread=${res.data.threadId}`;
            } catch(e) {}
        }

        async function deleteMyAd() {
            if(!confirm('هل أنت متأكد من حذف إعلانك بشكل نهائي؟')) return;
            try {
                await apiRequest('ads', 'POST', { action: 'delete_ad', ad_id: adId });
                window.location.href = 'index.php';
            } catch(e) {}
        }



        async function reportAd() {
            const reason = prompt('الرجاء كتابة سبب الإبلاغ بوضوح (مثال: محتوى مخالف، نصب، الخ):');
            if (!reason) return;
            
            try {
                await apiRequest('ads', 'POST', { action: 'report_ad', ad_id: adId, reason: reason });
                showToast('تم إرسال البلاغ للإدارة لمراجعته، شكراً لك.');
            } catch(e) {}
        }

        document.addEventListener('DOMContentLoaded', init);
    </script>
<?php require_once 'includes/footer.php'; ?>
