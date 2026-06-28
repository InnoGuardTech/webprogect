<?php
$pageTitle = 'المفضلة - حراج الفاخر';
require_once 'includes/header.php';
?>
    <style>
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1rem; }
        .ads-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    </style>

    <div class="container animate-fade-in">
        <h2 style="margin-top:0; margin-bottom:2rem; color:var(--primary); font-weight:900;">إعلاناتي المفضلة ❤️</h2>
        <div id="ads-container" class="ad-list">
            <div style="text-align:center; padding:4rem; color:var(--text-muted); font-weight:bold;">جاري التحميل...</div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        async function loadFavorites() {
            try {
                const res = await apiRequest('ads&action=favorites');
                const container = document.getElementById('ads-container');
                
                if (res.data.length === 0) {
                    container.innerHTML = '<div style="text-align:center; padding:4rem; color:var(--text-muted); font-weight:bold;">لا توجد إعلانات في المفضلة بعد.</div>';
                    return;
                }
                
                container.innerHTML = res.data.map(ad => `
                    <a href="ad.php?id=${ad.id}" class="ad-row animate-fade-in">
                        <div class="ad-row-main">
                            <img class="ad-row-thumb" src="${ad.image}" alt="${ad.title}">
                            <div class="ad-row-content">
                                <h3 class="ad-row-title">${ad.icon} ${ad.title}</h3>
                                <div class="ad-row-meta">
                                    <div class="ad-row-meta-item">📍 <span>${ad.city}</span></div>
                                    <div class="ad-row-meta-item">⏱️ <span>${ad.date}</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="ad-row-side">
                            <div class="ad-row-price">${ad.price}</div>
                            <div class="ad-row-city">${ad.category}</div>
                        </div>
                    </a>
                `).join('');
                
            } catch(e) {
                if (e.message.includes('Auth') || e.message.includes('تسجيل')) window.location.href = 'auth.php';
            }
        }
        
        document.addEventListener('DOMContentLoaded', loadFavorites);
    </script>
<?php require_once 'includes/footer.php'; ?>
