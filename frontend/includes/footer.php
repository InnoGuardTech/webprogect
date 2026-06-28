    <!-- Unified Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h4>حراج اليمن</h4>
                <p>المنصة الأولى لبيع وشراء السيارات والعقارات والأجهزة في اليمن. نوفر بيئة آمنة وموثوقة لجميع المستخدمين.</p>
            </div>
            <div class="footer-section">
                <h4>روابط هامة</h4>
                <a href="index.php">الرئيسية</a>
                <a href="commission.php">دفع العمولات</a>
                <a href="favorites.php">المفضلة</a>
            </div>
            <div class="footer-section">
                <h4>تواصل معنا</h4>
                <a href="#">الدعم الفني</a>
                <a href="#">شروط الاستخدام</a>
                <a href="#">سياسة الخصوصية</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> لموقع حراج اليمن</p>
        </div>
    </footer>

    <!-- Core App JS Utilities -->
    <script src="assets/js/app.js"></script>
    <script>
        function handleGlobalSearch() {
            const input = document.getElementById('global-search-input');
            if (input) {
                const val = input.value.trim();
                // If we are on index.php and loadAds exists, just call it or reload with query
                if (window.location.pathname.includes('index.php')) {
                    if (typeof debounceSearch === 'function') {
                        // Let index.php handle it if it wants, but reloading is cleaner for state
                        window.location.href = `index.php?q=${encodeURIComponent(val)}`;
                    } else {
                        window.location.href = `index.php?q=${encodeURIComponent(val)}`;
                    }
                } else {
                    window.location.href = `index.php?q=${encodeURIComponent(val)}`;
                }
            }
        }
        
        async function logout() {
            try {
                await apiRequest('auth', 'POST', { action: 'logout' });
                window.location.href = 'index.php';
            } catch (e) {}
        }
    </script>
</body>
</html>
